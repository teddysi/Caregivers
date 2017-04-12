<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Admin;
use App\HealthcarePro;
use App\Caregiver;
use App\Patient;
use App\Need;
use App\Material;


class UserController extends Controller
{
    

	public function dashboard()
	{
		return view('admin.admin_dashboard');
	}

	public function allUsers()
	{
		$users = User::all();

		return view('admin.admin_all_users', compact('users'));
	}

	public function details($id)
	{
		$user = User::find($id);
		$role = $this->roleToFullWord($user->id);

		return view('admin.admin_user_details', compact('user', 'role'));
	}

	/****ADMINS****/
	public function admins()
	{
		$admins = Admin::all();

		return view('admin.admin_admins', compact('admins'));
	}


	/****HEALTHCAREPROS****/
	public function healthcarepros()
	{
		$healthcarepros = HealthcarePro::all();

		return view('admin.admin_healthcarepros', compact('healthcarepros'));
	}

	public function healthcareproCaregivers($id)
	{
		$caregivers = HealthcarePro::find($id)->caregivers;

		return view('admin.admin_healthcarepro_caregivers', compact('caregivers'));
	}

	/****CAREGIVERS****/
	public function caregivers()
	{
		$caregivers = Caregiver::all();

		return view('admin.admin_caregivers', compact('caregivers'));
	}

	/****PATIENTS****/
	public function patients()
	{
		$patients = Patient::all();

		return view('admin.admin_patients', compact('patients'));
	}

	/****NEEDS****/
	public function needs()
	{
		$needs = Need::all();

		return view('admin.admin_needs', compact('needs'));
	}


	/****MATERIALS****/
	public function materials()
	{
		$materials = Material::all();

		return view('admin.admin_materials', compact('materials'));
	}


	/****HEALTHCAREPROS****/
	public function caregiverPatients($id)
	{
		$patients = Patient::where("caregiver_id", $id)->get();

		return view('admin.admin_caregiver_patients', compact('patients'));
	}

	public function patientNeeds($id)
	{
		
		$needs = Patient::find($id)->needs;

		return view('admin.admin_patient_needs', compact('needs'));
	}

	public function needMaterials($id)
	{
		$materials = Need::find($id)->materials;

		return view('admin.admin_need_materials', compact('materials'));
	}

	public function roleToFullWord($id)
	{
		$user = User::find($id);

		switch ($user->role) {
			case 'admin':
				return "Administrador";
				break;

			case 'healthcarepro':
				return "Profissional de Saúde";
				break;

			case 'caregiver':
				return "Cuidador";
				break;

			default:
				# code...
				break;
		}
	}
	
	

}
