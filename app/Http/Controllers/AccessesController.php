<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caregiver;
use App\Patient;
use App\Material;
use App\Access;
use App\User;
use App\Notification;
use Carbon\Carbon;

class AccessesController extends Controller
{
    public function create(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $patient_id = $request->input('patient_id');
        $material_id = $request->input('material_id');

        $caregiver = Caregiver::find($id);
        $patient = Patient::find($patient_id);
        $material = Material::find($material_id);

        if ($caregiver == null || $patient == null || $material == null) {
            return response('Não Encontrado', 404);
        }

        /*if (!$caregiver_token || $caregiver->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }*/

        $access = new Access;
        $access->caregiver_id = $id;
        $access->patient_id = $patient_id;
        $access->material_id = $material_id;
        $access->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' acedeu ao Material '.$material->name.' para cuidar o paciente '.$patient->name.'.';
        $notification->created_by = $id;
        $notification->type = 'access';
        $notification->save();
        
        return response()->json("Access created successfully");
    }

    public function export(Caregiver $caregiver)
    {
        $filename = 'Acessos de '.$caregiver->username.' em '.Carbon::now();
        $headers = [
                'Content-Type'        => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename='.$filename.'.csv'
        ];
        
        $accesses = collect();
        foreach ($caregiver->accesses as $access) {
            $arrayAccess = [];
            $arrayAccess['Paciente'] = $access->patient->name;
            $arrayAccess['Material'] = $access->material->name;
            $arrayAccess['Acedido Em'] = (string)$access->created_at;
            $accesses->push($arrayAccess);
        }
        $accesses = $accesses->toArray();

        array_unshift($accesses, array_keys($accesses[0]));

        $callback = function() use ($accesses) 
        {
            $file = fopen('php://output', 'w');
            foreach ($accesses as $access) { 
                fputcsv($file, $access);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers, JSON_UNESCAPED_UNICODE);
    }
}
