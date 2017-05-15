<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Caregiver;
use App\Patient;
use App\Need;
use App\Material;
use App\Http\Controllers\UsersController;
use DB;

class CaregiversController extends Controller
{
    public function patients(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        $patients = $caregiver->patients()->paginate(10);
        $patients->setPageName('patients');

        $notMyPatients = Patient::whereNull('caregiver_id')->paginate(10);
        $notMyPatients->setPageName('notMyPatients');

        return view('caregivers.patients',  compact('caregiver', 'patients', 'notMyPatients'));   
    }

    public function associatePatient(Caregiver $caregiver, Patient $patient)
    {
        $patient->caregiver_id = $caregiver->id;
        $patient->save();

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]); 
    }

    public function diassociatePatient(Caregiver $caregiver, Patient $patient)
    {
        $patient->caregiver_id = null;
        $patient->save();

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]);
    }

    public function materials(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        $patients = $caregiver->patients;
        $patientsNeeds = [];
        
        foreach ($patients as $patient) {
            foreach ($patient->needs as $need) {
                $needAlreadyExists = false;
                foreach ($patientsNeeds as $patientsNeed) {
                    if ($need->id == $patientsNeed->id) {
                        $needAlreadyExists = true;
                    }
                }

                if (!$needAlreadyExists) {
                    array_push($patientsNeeds, $need);
                }
            }
        }
        usort($patientsNeeds, array($this, 'cmp'));

        $materials = $caregiver->materials()->paginate(10);
        UsersController::changeTypeFormat($materials);

        $allMaterials = Material::all();

        return view('caregivers.materials',  compact('caregiver', 'materials', 'allMaterials', 'patientsNeeds'));   
    }

    public function associateMaterial(Request $request, Caregiver $caregiver)
    {
        if (count($caregiver->materials()->where('material_id', $request->input('material'))->get()) == 0) {
            $caregiver->materials()->attach($request->input('material'));
        }

        $need = Need::find($request->input('need'));
        if (count($need->materials()->where('material_id', $request->input('material'))->get()) == 0) {
            $need->materials()->attach($request->input('material'));
        }

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function diassociateMaterial(Caregiver $caregiver, Material $material)
    {
        $caregiver->materials()->detach($material->id);

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function rate(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(403);
		}

        $evaluations = $caregiver->evaluations()->paginate(10, ['*'], 'evaluations');
        $evaluations->setPageName('evaluations');

        $countedProceedings = DB::table('proceedings')
                                ->join('materials', 'proceedings.material_id', 'materials.id')
                                ->select('material_id', 'name', DB::raw('count(*) as total'))
                                ->groupBy('caregiver_id', 'material_id', 'name')
                                ->where('caregiver_id', $caregiver->id)
                                ->get();
                                
                                
        $rates = array('Mau', 'Normal', 'Bom', 'Muito Bom', 'Excelente');

        return view('caregivers.rate',  compact('caregiver', 'evaluations', 'countedProceedings', 'rates')); 
    }

    public function evaluate(Request $request, Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(403);
		}

        $caregiver->rate = $request->input('rate');
        $caregiver->save();

        return redirect('/'); 
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();

            if ($user->role == 'caregiver') {
                if (!$user->login_count) {
                    $user->login_count = 1;
                } else {
                    $user->login_count++;
                }
                
                $user->caregiver_token = str_random(60);
                $user->save();

                return response()->json($user);
            }
        }

        return response('Não Autorizado', 401);
    }

    public function patientsAPI(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $user = Caregiver::find($id);

        if ($user == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $user->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        //erro k aparecia era por causa de perder o array caso faça forget
        $c = collect();
        $patients = $user->patients;
        foreach ($patients as $patient) {
            $objectP = new \stdClass();
            $objectP->id = $patient->id;
            $objectP->email = $patient->email;
            $objectP->name = $patient->name;
            $objectP->location = $patient->location;
            $objectP->caregiver_id = $patient->caregiver_id;
            $objectP->created_by = $patient->created_by;
            $objectP->created_at = (string)$patient->created_at;
            $objectP->updated_at = (string)$patient->updated_at;
            $objectP->needs = [];
            foreach ($patient->needs as $need) {
                $objectN = new \stdClass();
                $objectN->id = $need->id;
                $objectN->description = $need->description;
                $objectN->created_by = $need->created_by;
                $objectN->created_at = (string)$need->created_at;
                $objectN->updated_at = (string)$need->updated_at;
                $objectN->materials = [];
                foreach ($need->materials as $material) {
                    if ($material->blocked != 1) {
                        $objectM = new \stdClass();
                        $objectM->id = $material->id;
                        $objectM->type = $material->type;
                        $objectM->description = $material->description;
                        $objectM->name = $material->name;

                        switch ($material->type) {
                            case 'text':
                                $objectM->body = $material->body;
                                break;

                            case 'image':
                                $objectM->url = $material->url;
                                $objectM->path = $material->path;
                                $objectM->mime = $material->mime;
                                break;

                            case 'video':
                                $objectM->url = $material->url;
                                $objectM->path = $material->path;
                                $objectM->mime = $material->mime;
                                break;

                            case 'annex':
                                if ($material->url != null) {
                                    $objectM->url = $material->url;
                                }

                                if ($material->path != null && $material->mime != null) {
                                    $objectM->path = $material->path;
                                    $objectM->mime = $material->mime;
                                }
                                break;

                            case 'emergencyContact':
                                $objectM->number = $material->number;
                                break;

                            default:
                                break;
                        }

                        $objectM->created_by = $material->created_by;
                        $objectM->created_at = (string)$material->created_at;
                        $objectM->updated_at = (string)$material->updated_at;

                        if ($material->type == 'composite') {
                            $objectM->materials = [];
                            $compositeMaterials = $material->materials()->withPivot('order')->orderBy('pivot_order', 'asc')->get();
                            foreach ($compositeMaterials as $compositeMaterial) {
                                $objectCM = new \stdClass();
                                $objectCM->id = $compositeMaterial->id;
                                $objectCM->type = $compositeMaterial->type;
                                $objectCM->description = $compositeMaterial->description;
                                $objectCM->name = $compositeMaterial->name;

                                switch ($compositeMaterial->type) {
                                    case 'text':
                                        $objectCM->body = $compositeMaterial->body;
                                        break;

                                    case 'image':
                                        $objectCM->url = $compositeMaterial->url;
                                        $objectCM->path = $compositeMaterial->path;
                                        $objectCM->mime = $compositeMaterial->mime;
                                        break;

                                    case 'video':
                                        $objectCM->url = $compositeMaterial->url;
                                        $objectCM->path = $compositeMaterial->path;
                                        $objectCM->mime = $compositeMaterial->mime;
                                        break;

                                    case 'annex':
                                        if ($compositeMaterial->url != null) {
                                            $objectCM->url = $compositeMaterial->url;
                                        }

                                        if ($compositeMaterial->path != null && $compositeMaterial->mime != null) {
                                            $objectCM->path = $compositeMaterial->path;
                                            $objectCM->mime = $compositeMaterial->mime;
                                        }
                                        break;

                                    case 'emergencyContact':
                                        $objectCM->number = $compositeMaterial->number;
                                        break;

                                    default:
                                        break;
                                }

                                $objectCM->created_by = $compositeMaterial->created_by;
                                $objectCM->created_at = (string)$compositeMaterial->created_at;
                                $objectCM->updated_at = (string)$compositeMaterial->updated_at;

                                array_push($objectM->materials, $objectCM);
                            }
                        }
                        array_push($objectN->materials, $objectM);
                    }
                }
                array_push($objectP->needs, $objectN);
            }
            $c->push($objectP);
        }

        return response()->json($c);      
    }

    public function caregiversMaterialsAPI(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver = Caregiver::find($id);

        if ($caregiver == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        $materials = [];
        $patients = $caregiver->patients;
        foreach ($patients as $p) {
            $needs = $p->needs;

            foreach ($needs as $n) {
                $n_materials = $n->materials; 
                
                foreach ($n_materials as $m) {

                    $material_exists = $this->materialExists($materials, $m);

                    if (!$material_exists) {
                        array_push($materials, $m);
                    }
                }
            }
        }

        return response()->json($materials);      
    }

    public function patientsMaterials(Request $request, $caregiver_id, $patient_id)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver = Caregiver::find($caregiver_id);
        $patient = Patient::find($patient_id);

        if ($caregiver == null || $patient == null) {
           return response('Não Encontrado', 404);
        }

        $caregivers_patient = false;
        foreach ($caregiver->patients as $p) {
            if ($p == $patient) {
                $caregivers_patient = true;
                break;
            }
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token || !$caregivers_patient) {
            return response('Não Autorizado', 401);
        }

        $materials = [];
        foreach ($patient->needs as $n) {
            $n_materials = $n->materials; 
                
            foreach ($n_materials as $m) {
                $material_exists = $this->materialExists($materials, $m);

                if (!$material_exists) {
                    array_push($materials, $m);
                }
            }
        }
        
        return response()->json($materials);
    }

    public function patientsNeeds(Request $request, $caregiver_id, $patient_id)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver = Caregiver::find($caregiver_id);
        $patient = Patient::find($patient_id);

        if ($caregiver == null || $patient == null) {
           return response('Não Encontrado', 404);
        }

        $caregivers_patient = false;
        foreach ($caregiver->patients as $p) {
            if ($p == $patient) {
                $caregivers_patient = true;
                break;
            }
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token || !$caregivers_patient) {
            return response('Não Autorizado', 401);
        }
        
        return response()->json($patient->needs);
    }

    public function patientsNeedsMaterials(Request $request, $caregiver_id, $patient_id, $need_id)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver = Caregiver::find($caregiver_id);
        $patient = Patient::find($patient_id);
        $need = Need::find($need_id);

        if ($caregiver == null || $patient == null || $need == null) {
           return response('Não Encontrado', 404);
        }

        $caregivers_patient = false;
        foreach ($caregiver->patients as $p) {
            if ($p == $patient) {
                $caregivers_patient = true;
                break;
            }
        }

        $patients_need = false;
        foreach ($patient->needs as $n) {
            if ($n->id == $need->id) {
                $patients_need = true;
                break;
            }
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token || !$caregivers_patient || !$patients_need) {
            return response('Não Autorizado', 401);
        }
        
        return response()->json($need->materials);
    }

    public function proceedings(Request $request, $caregiver_id)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver = Caregiver::find($caregiver_id);

        if ($caregiver == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        return response()->json($caregiver->proceedings);
    }

    private function materialExists($materials, $m)
    {
        $material_exists = false;
        foreach ($materials as $material) {
            if ($m->id == $material->id) {
                $material_exists = true;
                break;
            }
        }

        return $material_exists;
    }

    private function cmp($a, $b)
    {
        return $a->id > $b->id ? 1 : -1;
    }
}