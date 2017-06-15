<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caregiver;
use App\Patient;
use App\Material;
use App\Access;
use App\Notification;

class AccessesController extends Controller
{
    public function create(Request $request)
    {
        $caregiver_token = $request->header('Authorization');
        $caregiver_id = $request->input('caregiver_id');
        $patient_id = $request->input('patient_id');
        $material_id = $request->input('material_id');

        $caregiver = Caregiver::find($caregiver_id);
        $patient = Patient::find($patient_id);
        $material = Material::find($material_id);

        if ($caregiver == null || $patient == null || $material == null) {
            return response('Não Encontrado', 404);
        }

        /*if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }*/

        $access = new Access;
        $access->caregiver_id = $caregiver_id;
        $access->patient_id = $patient_id;
        $access->material_id = $material_id;
        $access->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' acedeu ao Material '.$material->name.' para cuidar o paciente '.$patient->name.'.';
        $notification->created_by = $caregiver_id;
        $notification->type = 'access';
        $notification->save();
        
        return response()->json("Access created successfully");
    }
}
