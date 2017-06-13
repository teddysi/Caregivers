<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caregiver;
use App\Patient;
use App\Material;
use App\Proceeding;
use App\Notification;

class ProceedingsController extends Controller
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

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        $proceeding = new Proceeding;
        $proceeding->caregiver_id = $caregiver_id;
        $proceeding->patient_id = $patient_id;
        $proceeding->material_id = $material_id;
        $proceeding->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' utilizou o Material '.$material->name.' para cuidar o paciente '.$patient->name.'.';
        $notification->created_by = $caregiver_id;
        $notification->type = 'proceeding';
        $notification->save();
        
        return response()->json("Proceeding created successfully");
    }

    public function update(Request $request, $proceeding_id)
    {
        $caregiver_token = $request->header('Authorization');
        $material_id = $request->input('material_id');
        $note = $request->input('note');
        
        $proceeding = Proceeding::find($proceeding_id);
        $caregiver = Caregiver::find($proceeding->caregiver_id);
        $material = Material::find($material_id);

        if ($proceeding == null || $caregiver == null || $material == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        $proceeding->material_id = $material_id;
        $proceeding->note = $note;
        $proceeding->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' atualizou o Procedimento realizado ao '.$patient->name.'.';
        $notification->created_by = $proceeding->caregiver_id;
        $notification->type = 'proceeding';
        $notification->save();
        
        return response()->json("Proceeding updated successfully");
    }
}
