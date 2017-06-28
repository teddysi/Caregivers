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
use Auth;

class AccessesController extends Controller
{
    public function create(Request $request, $id)
    {
        $caregiverToken = $request->header('Authorization');
        $patientId = $request->input('patient_id');
        $materialId = $request->input('material_id');

        $caregiver = Caregiver::find($id);
        $patient = Patient::find($patientId);
        $material = Material::find($materialId);

        if ($caregiver == null || $patient == null || $material == null) {
            return response('Não Encontrado', 404);
        }

        /*if (!$caregiverToken || $caregiver->caregiver_token != $caregiverToken) {
            return response('Não Autorizado', 401);
        }*/

        $access = new Access;
        $access->caregiver_id = $id;
        $access->patient_id = $patientId;
        $access->material_id = $materialId;
        $access->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' acedeu ao Material '.$material->name.' para cuidar o utente '.$patient->name.'.';
        $notification->created_by = $id;
        $notification->type = 'access';
        $notification->save();
        
        return response()->json("Access created successfully");
    }

    public function export()
    {
        $filename = 'Acessos dos cuidadores de '.Auth::user()->name.' em '.Carbon::now();
        $headers = [
                'Content-Type'        => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename='.$filename.'.csv'
        ];
        
        $accesses = collect();
        foreach (Auth::user()->caregivers as $caregiver) {
            foreach ($caregiver->accesses as $access) {
                $arrayAccess = [];
                $arrayAccess['Cuidador'] = $access->caregiver->name;
                $arrayAccess['Utente'] = $access->patient->name;
                $arrayAccess['Material'] = $access->material->name;
                $arrayAccess['Acedido Em'] = (string)$access->created_at;
                $accesses->push($arrayAccess);
            }
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
