<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Session;
use App\User;
use App\Admin;
use App\HealthcarePro;
use App\Caregiver;
use App\Patient;
use App\Need;
use App\Material;
use App\Http\Controllers\MaterialController;

class UserController extends Controller
{

	private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	    'min'    => ':attribute tem que ter pelo menos 4 letras/digitos.',
	    'confirmed' => 'As passwords têm que ser iguais nos dois campos',
	];
    

	public function dashboard()
	{
		if (Auth::guest()) {
			return view('auth.login');
		}
		
		if (Auth::user()->blocked == 1) {
            Auth::logout();
           	Session::flash('blockedAccount', "Your account as been blocked.");
            return back();
        }

        if (Auth::user()->role == 'admin') {
			$materials = Material::paginate(6);
			$this->changeTypeFormat($materials);
            return view('dashboard.admin', compact('materials'));
        } elseif (Auth::user()->role == 'healthcarepro') {
			return view('dashboard.healthcarepro');
		}

        Auth::logout();
    	return back();
	}

	private function changeTypeFormat($materials)
	{
		foreach ($materials as $material) {
			MaterialController::changeTypeFormat($material);
		}
	}

	public function allUsers()
	{
		$users = User::all();

		return view('users.all_users', compact('users'));
	}

	public function details($id)
	{
		$user = User::find($id);
		$role = $this->roleToFullWord($user->id);

		return view('users.user_details', compact('user', 'role'));
	}

	/****ADMINS****/
	public function admins()
	{
		$admins = Admin::all();

		return view('admins.admins', compact('admins'));
	}


	/****HEALTHCAREPROS****/
	public function healthcarepros()
	{
		$healthcarepros = HealthcarePro::all();

		return view('healthcarepros.healthcarepros', compact('healthcarepros'));
	}

	public function healthcareproCaregivers($id)
	{
		$caregivers = HealthcarePro::find($id)->caregivers;

		return view('healthcarepros.healthcarepro_caregivers', compact('caregivers'));
	}

	/****CAREGIVERS****/
	public function caregivers()
	{
		$caregivers = Caregiver::all();

		return view('caregivers.caregivers', compact('caregivers'));
	}

	/****PATIENTS****/
	public function patients()
	{
		$patients = Patient::all();

		return view('patients.patients', compact('patients'));
	}

	/****HEALTHCAREPROS****/
	public function caregiverPatients($id)
	{
		$patients = Patient::where("caregiver_id", $id)->get();

		return view('caregivers.caregiver_patients', compact('patients'));
	}

	public function patientNeeds($id)
	{
		
		$needs = Patient::find($id)->needs;

		return view('patients.patient_needs', compact('needs'));
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
	
	public function createUser($role)
	{	
		$user = new User();
		$user->role = $role;

		return view('users.create_user', compact('user'));
	}

	public function saveAdmin(Request $request)
	{

		$this->validate($request, [
				'username' => 'required|min:4|unique:users',
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|min:6|confirmed',
				
			], $this->messages);

		$user = new Admin($request->all());
		$user->username = $request->input('username');
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->password = bcrypt($user->password );

		$user->save();

		return redirect('/admins');
	}

	public function saveHealthcarepro(Request $request)
	{
		$this->validate($request, [
				'username' => 'required|min:4|unique:users',
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|min:6|confirmed',
				'job' => 'required',
				'facility' => 'required',
			], $this->messages);

		$user = new HealthcarePro($request->all());
		$user->username = $request->input('username');
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->password = bcrypt($user->password );

		$user->save();

		return redirect('/healthcarepros');
		
	}

	public function saveCaregiver(Request $request)
	{
		$this->validate($request, [
				'username' => 'required|min:4|unique:users',
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|min:6|confirmed',
			], $this->messages);

		$user = new Caregiver($request->all());
		$user->username = $request->input('username');
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->password = bcrypt($user->password );
		$user->rate = 'Sem avaliação';
		$user->save();

		return redirect('/caregivers');
	}

	public function deleteHealthcarepro($id)
	{
		$user = HealthcarePro::find($id);
        $user->delete();

        return redirect('/healthcarepros');
	}

	public function deleteAdmin($id)
	{
		$user = Admin::find($id);
        $user->delete();

        return redirect('/admins');
	}

	public function deleteCaregiver($id)
	{
		$user = Caregiver::find($id);
        $user->delete();

        return redirect('/caregivers');
	}

	public function updateUser($id)
	{	
		$user =  User::find($id);
		$role = $user->role;

		if($role == 'admin')
		{
			$updateUser = Admin::find($id);
			

			return view('users.update_user', compact('updateUser'));

		}

		if($role == 'healthcarepro')
		{
			$updateUser = new HealthcarePro();
			$updateUser->role = $role;
			$updateUser->name = $user->name;
			$updateUser->email = $user->email;
			$updateUser->job = $user->job;
			$updateUser->facility = $user->facility;

			return view('users.update_user', compact('updateUser'));

		}

		if($role == 'caregiver')
		{
			$updateUser = new HealthcarePro();
			$updateUser->role = $role;
			$updateUser->name = $user->name;
			$updateUser->email = $user->email;

			return view('users.update_user', compact('updateUser'));

		}
	}

	public function updateAdmin(Request $request)
	{

		$user =  User::find($request->id);
		$role = $user->role;

		if ($role == 'admin')
		{
			$this->validate($request, [
					'name' => 'required',
					'email' => [
							'required', 'email' , Rule::unique('users')->ignore($request->id, 'id'),
							],
		
				], $this->messages);

			$admin = Admin::find($request->id);
			$admin->name = $request->name;
			$admin->email = $request->email;

			if ($request->input('password'))
			{
				$this->validate($request, [
						'password' => 'confirmed|min:4',
					], $this->messages);

				$admin->password = bcrypt($request->input('password'));
			}

			$admin->save();

			return redirect('/admins');
		}

		if ($role == 'healthcarepro')
		{
			$this->validate($request, [
					'name' => 'required',
					'email' => [
							'required', 'email' , Rule::unique('users')->ignore($request->id, 'id'),
							],
		
				], $this->messages);

			$healthcarepro = HealthcarePro::find($request->id);
			$healthcarepro->name = $request->name;
			$healthcarepro->email = $request->email;
			$healthcarepro->job = $request->job;
			$healthcarepro->facility = $request->facility;

			if ($request->input('password'))
			{
				$this->validate($request, [
						'password' => 'confirmed|min:4',
					], $this->messages);

				$healthcarepro->password = bcrypt($request->input('password'));
			}

			$healthcarepro->save();

			return redirect('/healthcarepros');
		}

		if ($role == 'caregiver')
		{
			$this->validate($request, [
					'name' => 'required',
					'email' => [
							'required', 'email' , Rule::unique('users')->ignore($request->id, 'id'),
							],
		
				], $this->messages);

			$caregiver = Caregiver::find($request->id);
			$caregiver->name = $request->name;
			$caregiver->email = $request->email;

			if ($request->input('password'))
			{
				$this->validate($request, [
						'password' => 'confirmed|min:4',
					], $this->messages);

				$caregiver->password = bcrypt($request->input('password'));
			}

			$caregiver->save();

			return redirect('/caregivers');
		}
	}

}
