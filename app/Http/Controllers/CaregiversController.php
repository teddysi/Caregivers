<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Caregiver;
use App\Patient;
use App\Need;
use App\Http\Controllers\UsersController;

class CaregiversController extends Controller
{
    public function patients(Caregiver $caregiver)
    {
        $patients = $caregiver->patients()->paginate(10);
        $notMyPatients = Patient::whereNull('caregiver_id')->paginate(10);

        return view('caregivers.patients',  compact('caregiver', 'patients', 'notMyPatients'));   
    }

    public function associate(Caregiver $caregiver, Patient $patient)
    {
        $patient->caregiver_id = $caregiver->id;
        $patient->save();

        $patients = $caregiver->patients()->paginate(10);
        $notMyPatients = Patient::whereNull('caregiver_id')->paginate(10);

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]); 
    }

    public function diassociate(Caregiver $caregiver, Patient $patient)
    {
        $patient->caregiver_id = null;
        $patient->save();

        $patients = $caregiver->patients()->paginate(10);
        $notMyPatients = Patient::whereNull('caregiver_id')->paginate(10);

        return redirect()->route('caregivers.patients', ['caregiver' => $caregiver->id]);
    }

    public function materials(Caregiver $caregiver)
    {
        $materials = $caregiver->materials()->paginate(10);
        UsersController::changeTypeFormat($materials);

        return view('caregivers.materials',  compact('caregiver', 'materials'));   
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

        return response()->json($user->patients);      
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
}