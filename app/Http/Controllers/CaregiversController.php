<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class CaregiversController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();

            if ($user->role == 'caregiver') {
                $user->caregiver_token = str_random(60);
                $user->save();

                return response()->json($user);
            }
        }

        return response('Não Autorizado', 401);
    }

    public function patients(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $user = User::find($id);

        if ($user == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $user->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        return response()->json($user->patients);      
    }

    public function materials(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $user = User::find($id);

        if ($user == null) {
           return response('Não Encontrado', 404);
        }

        if (!$caregiver_token || $user->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }

        $materials = [];
        $patients = $user->patients;
        foreach ($patients as $p) {
            $needs = $p->needs;

            foreach ($needs as $n) {
                $n_materials = $n->materials; 
                
                foreach ($n_materials as $m) {
                    array_push($materials, $m);
                }
            }
        }

        return response()->json(array_unique($materials));      
    }
}