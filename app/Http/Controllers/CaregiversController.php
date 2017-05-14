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
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        if ($patient->caregiver_id != null) {
            abort(403);
		}
        $patient->caregiver_id = $caregiver->id;
        $patient->save();

        $log = new Log();
		$log->performed_task = 'Associou o Paciente: ' . $patient->name. 'ao Cuidador: ' . $caregiver->username;
		$log->user_id = Auth::user()->id;
		$log->save();

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]); 
    }

    public function diassociatePatient(Caregiver $caregiver, Patient $patient)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        if ($patient->caregiver_id != $caregiver->id) {
            abort(403);
        }
        $patient->caregiver_id = null;
        $patient->save();

        $log = new Log();
		$log->performed_task = 'Desassociou o Paciente: ' . $patient->name. 'do Cuidador: ' . $caregiver->username;
		$log->user_id = Auth::user()->id;
		$log->save();

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
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        $material = Material::find($request->input('material'));
        if (!$caregiver->materials->contains('id', $request->input('material'))) {
            $caregiver->materials()->attach($request->input('material'));

            $log = new Log();
            $log->performed_task = 'Associou o Material: ' . $material->name. 'ao Cuidador: ' . $caregiver->username;
            $log->user_id = Auth::user()->id;
            $log->save();
        }

        $need = Need::find($request->input('need'));
        if ($need->materials->contains('id', $request->input('material'))) {
            $need->materials()->attach($request->input('material'));

            $log = new Log();
            $log->performed_task = 'Associou o Material: ' . $material->name. 'à Necessidade: ' . $need->description;
            $log->user_id = Auth::user()->id;
            $log->save();
        }

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function diassociateMaterial(Caregiver $caregiver, Material $material)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        if (!$caregiver->materials->contains('id', $material->id)) {
            abort(403);
        }
        $caregiver->materials()->detach($material->id);

        $log = new Log();
        $log->performed_task = 'Desassociou o Material: ' . $material->name. 'do Cuidador: ' . $caregiver->username;
        $log->user_id = Auth::user()->id;
        $log->save();

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function rate(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}

        $countedProceedings = DB::table('proceedings')
                                ->join('materials', 'proceedings.material_id', 'materials.id')
                                ->select('material_id', 'name', DB::raw('count(*) as total'))
                                ->groupBy('caregiver_id', 'material_id', 'name')
                                ->where('caregiver_id', $caregiver->id)
                                ->get();
                                
        $rates = array('Mau', 'Normal', 'Bom', 'Muito Bom', 'Excelente');

        return view('caregivers.rate',  compact('caregiver', 'countedProceedings', 'rates')); 
    }

    public function evaluate(Request $request, Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(401);
		}
        $caregiver->rate = $request->input('rate');
        $caregiver->save();

        $log = new Log();
        $log->performed_task = 'Atribuiu uma classificação ao Cuidador: ' . $caregiver->username;
        $log->user_id = Auth::user()->id;
        $log->save();

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

        $patientsCollection = collect();
        $patients = $user->patients;
        foreach ($patients as $patient) {
            $objectP = new \stdClass();
            $this->buildPatient($objectP, $patient);
            foreach ($patient->needs as $need) {
                $objectN = new \stdClass();
                $this->buildNeed($objectN, $need);
                foreach ($need->materials as $material) {
                    if ($material->blocked != 1) {
                        $objectM = new \stdClass();
                        $this->buildMaterial($objectM, $material);

                        array_push($objectN->materials, $objectM);
                    }
                }
                array_push($objectP->needs, $objectN);
            }
            $patientsCollection->push($objectP);
        }

        return response()->json($patientsCollection);      
    }

    private function buildPatient($objectP, $patient)
    {
        $objectP->id = $patient->id;
        $objectP->email = $patient->email;
        $objectP->name = $patient->name;
        $objectP->location = $patient->location;
        $objectP->caregiver_id = $patient->caregiver_id;
        $objectP->created_by = $patient->created_by;
        $objectP->created_at = (string)$patient->created_at;
        $objectP->updated_at = (string)$patient->updated_at;
        $objectP->needs = [];
    }

    private function buildNeed($objectN, $need)
    {
        $objectN->id = $need->id;
        $objectN->description = $need->description;
        $objectN->created_by = $need->created_by;
        $objectN->created_at = (string)$need->created_at;
        $objectN->updated_at = (string)$need->updated_at;
        $objectN->materials = [];
    }

    private function buildMaterial($objectM, $material)
    {
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
                if ($compositeMaterial->blocked != 1) {
                    $objectCM = new \stdClass();
                    $this->buildMaterial($objectCM, $compositeMaterial);

                    array_push($objectM->materials, $objectCM);
                }
            }
        }
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

    private function cmp($a, $b)
    {
        return $a->id > $b->id ? 1 : -1;
    }
}