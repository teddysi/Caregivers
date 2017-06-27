<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Caregiver;
use App\Patient;
use App\Need;
use App\Material;
use App\Log;
use App\Http\Controllers\UsersController;
use DB;

class CaregiversController extends Controller
{
    public function patients(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(403);
		}

        $patients = $caregiver->patients()->paginate(10, ['*'], 'patients');
        $patients->setPageName('patients');

        $notMyPatients = Patient::whereNull('caregiver_id')->paginate(10, ['*'], 'notMyPatients');
        $notMyPatients->setPageName('notMyPatients');

        return view('caregivers.patients',  compact('caregiver', 'patients', 'notMyPatients'));   
    }

    public function associatePatient(Caregiver $caregiver, Patient $patient)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id) || $patient->caregiver_id != null) {
			abort(403);
		}
        $patient->caregiver_id = $caregiver->id;
        $patient->save();

        foreach ($patient->needs as $need) {
            foreach ($need->materials as $material) {
                if (!$caregiver->materials->contains("id", $material->id)) {
                    $caregiver->materials()->attach($material->id);
                }
            }
        }

        $log = new Log();
		$log->performed_task = 'Foi associado o Paciente: '.$patient->name.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $caregiver->id;
		$log->save();

        $log = new Log();
		$log->performed_task = 'Foi associado ao Cuidador: '.$caregiver->username.'.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]); 
    }

    public function diassociatePatient(Caregiver $caregiver, Patient $patient)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id) || $patient->caregiver_id != $caregiver->id) {
			abort(403);
		}
        $patient->caregiver_id = null;
        $patient->save();

        $log = new Log();
		$log->performed_task = 'Foi desassociado o Paciente: '.$patient->name.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $caregiver->id;
		$log->save();

        $log = new Log();
		$log->performed_task = 'Foi desassociado do Cuidador: '.$caregiver->username.'.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]);
    }

    public function materials(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(403);
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
			abort(403);
		}

        $material = Material::find($request->input('material'));
        if (!$caregiver->materials->contains('id', $request->input('material'))) {
            $caregiver->materials()->attach($request->input('material'));

            $log = new Log();
            $log->performed_task = 'Foi associado o Material: '.$material->name.'.';
            $log->done_by = Auth::user()->id;
		    $log->user_id = $caregiver->id;
            $log->save();

            $log = new Log();
            $log->performed_task = 'Foi associado ao Cuidador: '.$caregiver->username.'.';
            $log->done_by = Auth::user()->id;
		    $log->material_id = $material->id;
            $log->save();
        }

        if ($request->input('need')) {
            $need = Need::find($request->input('need'));
            if (!$need->materials->contains('id', $request->input('material'))) {
                $need->materials()->attach($request->input('material'));

                $log = new Log();
                $log->performed_task = 'Foi associado o Material: '.$material->name.'.';
                $log->done_by = Auth::user()->id;
                $log->need_id = $need->id;
                $log->save();

                $log = new Log();
                $log->performed_task = 'Foi associada à Necessidade: '.$need->description.'.';
                $log->done_by = Auth::user()->id;
                $log->material_id = $material->id;
                $log->save();
            }
        }

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function diassociateMaterial(Caregiver $caregiver, Material $material)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id) || !$caregiver->materials->contains('id', $material->id)) {
			abort(403);
		}
        $caregiver->materials()->detach($material->id);

        $log = new Log();
        $log->performed_task = 'Foi desassociado o Material: '.$material->name.'.';
        $log->done_by = Auth::user()->id;
		$log->user_id = $caregiver->id;
        $log->save();

        $log = new Log();
        $log->performed_task = 'Foi desassociado do Cuidador: '.$caregiver->username.'.';
        $log->done_by = Auth::user()->id;
		$log->material_id = $material->id;
        $log->save();

        return redirect()->route('caregivers.materials', ['caregiver' => $caregiver->id]); 
    }

    public function rate(Caregiver $caregiver)
    {
        if (!$caregiver->healthcarePros->contains('id', Auth::user()->id)) {
			abort(403);
		}

        $evaluations = $caregiver->evaluations()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'evaluations');
        $evaluations->setPageName('evaluations');

        $countedAccesses = DB::table('accesses')
                                ->join('materials', 'accesses.material_id', 'materials.id')
                                ->select('material_id', 'name', DB::raw('count(*) as total'))
                                ->groupBy('caregiver_id', 'material_id', 'name')
                                ->where('caregiver_id', $caregiver->id)
                                ->orderBy('total', 'desc')
                                ->paginate(10, ['*'], 'countedAccesses');
        $countedAccesses->setPageName('countedAccesses');
                                
        return view('caregivers.rate',  compact('caregiver', 'evaluations', 'countedAccesses')); 
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();
            
            if ($user->blocked) {
                return response('Utilizador Bloqueado', 401);
            }

            if ($user->role == 'caregiver') {
                if (!$user->login_count) {
                    $user->login_count = 1;
                } else {
                    $user->login_count++;
                }
                
                $user->caregiver_token = str_random(60);
                $user->save();

                $caregiver = new \stdClass();
                $this->buildCaregiver($caregiver, $user);

                return response()->json($caregiver);
            }
        }

        return response('Não Autorizado', 401);
    }

    private function buildCaregiver($objectC, $caregiver)
    {
        $objectC->id = $caregiver->id;
        $objectC->username = $caregiver->username;
        $objectC->name = $caregiver->name;
        $objectC->email = $caregiver->email;
        $objectC->role = $caregiver->role;
        $objectC->location = $caregiver->location;
        $objectC->login_count = $caregiver->login_count;
        $objectC->caregiver_token = $caregiver->caregiver_token;
        $objectC->blocked = $caregiver->blocked;
        $objectC->created_by = $caregiver->created_by;
        $objectC->created_at = (string) $caregiver->created_at;
        $objectC->updated_at = (string) $caregiver->updated_at;
        $objectC->contacts = [];

        foreach ($caregiver->healthcarePros as $healthcarePro) {
            $contact = new \stdClass();
            $contact->name = $healthcarePro->name;
            $contact->email = $healthcarePro->email;
            array_push($objectC->contacts, $contact);
        }
    }

    public function patientsAPI(Request $request, $id)
    {
        $caregiverToken = $request->header('Authorization');
        $user = Caregiver::find($id);

        if ($user == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiverToken || $user->caregiver_token != $caregiverToken) {
            return response('Não Autorizado', 401);
        }

        if ($user->blocked) {
            return response('Utilizador Bloqueado', 401);
        }

        $patients = $user->patients;
        $objectX = new \stdClass();
        $this->buildJson($objectX);

        foreach ($user->quizs as $quiz) {
            if(!$quiz->blocked) {
                $objectQuiz = new \stdClass();
                $this->buildQuiz($objectQuiz, $quiz, 'caregiver', $user->id, $user->name);
                array_push($objectX->quizs, $objectQuiz);
            }
        }

        foreach ($patients as $patient) {
            $objectP = new \stdClass();
            $this->buildPatient($objectP, $patient);
            foreach ($patient->needs as $need) {
                $objectN = new \stdClass();
                $this->buildNeed($objectN, $need);
                foreach ($need->materials as $material) {
                    if ($material->blocked != 1 && $user->materials->contains('id', $material->id)) {
                        $objectM = new \stdClass();
                        $this->buildMaterial($objectM, $material, $id);

                        array_push($objectN->materials, $objectM);
                    }
                    foreach ($material->quizs($user->id)->get() as $quiz) {
                        if(!$quiz->blocked) {
                            $objectQuiz = new \stdClass();
                            $this->buildQuiz($objectQuiz, $quiz, 'material', $material->id, $material->name);
                            array_push($objectM->quizs, $objectQuiz);
                        }
                    }
                }
                array_push($objectP->needs, $objectN);
            }
            array_push($objectX->patients, $objectP);
            
            foreach ($patient->quizs as $quiz) {
                if(!$quiz->blocked) {
                    $objectQuiz = new \stdClass();
                    $this->buildQuiz($objectQuiz, $quiz, 'patient', $patient->id, $patient->name);
                    array_push($objectP->quizs, $objectQuiz);
                }
            }
        }

        return response()->json($objectX);      
    }

    private function buildJson($objectX)
    {
        $objectX->quizs = [];
        $objectX->patients = [];
    }

    private function buildQuiz($objectQuiz, $quiz, $type, $id, $name)
    {
        $objectQuiz->id = $quiz->id;
        $objectQuiz->name = $quiz->name;
        $objectQuiz->blocked = $quiz->blocked;
        $objectQuiz->reference = $type;
        $objectQuiz->reference_id = $id;
        $objectQuiz->reference_name = $name;
        $objectQuiz->created_at = (string) $quiz->created_at;
        $objectQuiz->updated_at = (string) $quiz->updated_at;
        $objectQuiz->questions = [];

        foreach ($quiz->questions as $question) {
            if(!$question->blocked) {
                $objectQuestion = new \stdClass();
                $this->buildQuestion($objectQuestion, $question);
                array_push($objectQuiz->questions, $objectQuestion);
            }
        }
    }

    private function buildQuestion($objectQuestion, $question)
    {
        $objectQuestion->id = $question->id;
        $objectQuestion->question = $question->question;
        $objectQuestion->type = $question->type;
        $objectQuestion->values = $question->values;
        $objectQuestion->blocked = $question->blocked;
        $objectQuestion->created_by = $question->created_by;
        $objectQuestion->created_at = (string) $question->created_at;
        $objectQuestion->updated_at = (string) $question->updated_at;
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
        $objectP->quizs = [];
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

    private function buildMaterial($objectM, $material, $caregiver_id)
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

        $lastEvaluationSubmitted = $material->evaluations->where('submitted_by', $caregiver_id)->last();
        if ($lastEvaluationSubmitted) {
            switch ($lastEvaluationSubmitted->difficulty) {
                case 'Fácil':
                    $objectM->evaluation = 1;
                    break;
                case 'Médio':
                    $objectM->evaluation = 0;
                    break;
                case 'Difícil':
                    $objectM->evaluation = -1;
                    break;
                default:
                    break;
            }
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
                    $this->buildMaterial($objectCM, $compositeMaterial, $caregiver_id);

                    array_push($objectM->materials, $objectCM);
                }
            }
        }

        $objectM->quizs = [];
    }

    private function cmp($a, $b)
    {
        return $a->id > $b->id ? 1 : -1;
    }
}