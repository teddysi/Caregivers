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
use App\Http\Controllers\MaterialsController;

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
			$users = User::paginate(10, ['*'], 'users');
			foreach ($users as $user) {
				$this->roleToFullWord($user);
			}
			$users->setPageName('users');

			$materials = Material::paginate(10, ['*'], 'materials');
			$this->changeTypeFormat($materials);
			$materials->setPageName('materials');
            return view('dashboard.admin', compact('users', 'materials'));
        } elseif (Auth::user()->role == 'healthcarepro') {
			return view('dashboard.healthcarepro');
		}

        Auth::logout();
    	return back();
	}

		public function index(Request $request)
	{
		$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['name' => '', 'email' => '', 'role' => '', 'sort' => '', 'pages' => '', 'blocked' => ''];

        if ($request->has('dashboard')) {
            $this->saveDataFieldsToSession($request);
            $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
        } else {
            if ($this->isRequestDataEmpty($request)) {
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            } else {
                $this->saveDataFieldsToSession($request);
                $searchData = $this->retrieveDataFieldsFromSessionToArray($request, $searchData);
            }
        }

		if (!empty($searchData['name'])) {
           	$where[] = ['name', 'like', '%'.$searchData['name'].'%'];
        }

        if (!empty($searchData['email'])) {
        	$where[] = ['email', 'like', '%'.$searchData['email'].'%'];
        }

		if (!empty($searchData['role'])) {
			if($searchData['role'] != 'all') {
                $where[] = ['role', 'like', '%'.$searchData['role'].'%'];
            }
        }

		if (!empty($searchData['blocked'])) {
            if($searchData['blocked'] == 'just_blocked') {
                $where[] = ['blocked', 1];
            } elseif($searchData['blocked'] == 'just_unblocked') {
                $where[] = ['blocked', 0];
            }
        }

		if (!empty($searchData['sort'])) {
            if($searchData['sort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['sort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['sort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['sort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            } elseif($searchData['sort'] == 'email_az') {
                $col = 'email';
                $order = 'asc';
            } elseif($searchData['sort'] == 'email_za') {
                $col = 'email';
                $order = 'desc';
            } elseif($searchData['sort'] == 'role_az') {
                $col = 'role';
                $order = 'asc';
            } elseif($searchData['sort'] == 'role_za') {
                $col = 'role';
                $order = 'desc';
            }
        }

		if (!empty($searchData['pages'])) {
            $pages = $searchData['pages'];
        }

		$users = User::where($where)->orderBy($col, $order)->paginate((int)$pages);
		foreach ($users as $user) {
			$this->roleToFullWord($user);
		}

		return view('users.index', compact('users','searchData'));
	}	

	public function show(User $user)
	{
		$this->roleToFullWord($user);

		return view('users.show', compact('user'));
	}

	public function create($role)
	{	
		return view('users.create', compact('role'));
	}

	public function store(Request $request)
	{	
		$this->validate($request, [
			'username' => 'required|min:4|unique:users',
			'name' => 'required|min:4',
			'email' => 'required|email|unique:users',
			'job' => 'nullable|min:4|required_if:role,healthcarepro',
			'facility' => 'nullable|min:4|required_if:role,healthcarepro',
			'location' => 'nullable|min:4|required_if:role,caregiver',
			'password' => 'required|min:6|confirmed',
		], $this->messages);

		$user;
		switch ($request->input('role')) {
			case 'admin':
				$user = new Admin();
				break;

			case 'healthcarepro':
				$user = new HealthcarePro();
				$user->job = $request->input('job');
				$user->facility = $request->input('facility');
				break;

			case 'caregiver':
				$user = new Caregiver();
				$user->location = $request->input('location');
				$user->rate = 'Sem avaliação';
				$user->created_by = Auth::user()->id;
				break;

			default:
				break;
		}

		$user->username = $request->input('username');
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->password = bcrypt($request->input('password'));

		$user->save();

		return redirect('/');
	}

	public function edit(User $user) {
		$this->roleToFullWord($user);
		return view('users.edit', compact('user'));
	}

	public function update(Request $request, User $user)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,'.$user->id,
			'job' => 'nullable|min:4|required_if:role,healthcarepro',
			'facility' => 'nullable|min:4|required_if:role,healthcarepro',
			'location' => 'nullable|min:4|required_if:role,caregiver',
		], $this->messages);

		$user->name = $request->name;
		$user->email = $request->email;
		$user->job = $request->job;
		$user->facility = $request->facility;
		$user->location = $request->location;

		if ($request->input('password')) {
			$this->validate($request, [
				'password' => 'required|confirmed|min:6',
			], $this->messages);

			$user->password = bcrypt($request->input('password'));
		}

		$user->save();

		return redirect('/');
	}
	
	public function toggleBlock(Request $request, User $user)
	{
		if ($user->blocked == 0) {
            $user->blocked = 1;
            $user->save();

            //$request->session()->flash('blockedStatus', "User $user->name blocked.");
        } elseif ($user->blocked == 1) {
            $user->blocked = 0;
            $user->save();

            //$request->session()->flash('blockedStatus', "User $user->name unblocked.");
        }

        return back();
	}

	private function roleToFullWord($user)
	{
		switch ($user->role) {
			case 'admin':
				$user->role = 'Administrador';
				break;

			case 'healthcarepro':
				$user->role = 'Profissional de Saúde';
				break;

			case 'caregiver':
				$user->role = 'Cuidador';
				break;

			default:
				break;
		}
	}

	private function changeTypeFormat($materials)
	{
		foreach ($materials as $material) {
			MaterialsController::changeTypeFormat($material);
		}
	}

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('name', $request->input('name'));
        $request->session()->put('email', $request->input('email'));
        $request->session()->put('role', $request->input('role'));
		$request->session()->put('sort', $request->input('sort'));
        $request->session()->put('pages', $request->input('pages'));
        $request->session()->put('blocked', $request->input('blocked'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['name'] = $request->session()->get('name');
        $searchData['email'] = $request->session()->get('email');
        $searchData['role'] = $request->session()->get('role');
        $searchData['sort'] = $request->session()->get('sort');
        $searchData['pages'] = $request->session()->get('pages');
		$searchData['blocked'] = $request->session()->get('blocked');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('name') && !$request->has('email')
            && !$request->has('role') && !$request->has('sort')
            && !$request->has('pages') && !$request->has('blocked')) {
            return true;
        }
        return false;
    }

	public function allUsers()
	{
		$users = User::all();

		return view('users.all_users', compact('users'));
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

	public function caregiverPatients($id)
	{
		$patients = Patient::where("caregiver_id", $id)->get();

		return view('caregivers.caregiver_patients', compact('patients'));
	}
}
