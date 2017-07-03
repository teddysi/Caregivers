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
use App\Quiz;
use App\Question;
use App\Log;
use App\Http\Controllers\MaterialsController;
use DB;

class UsersController extends Controller
{
	private $messages = [
	    'unique' =>  ':Attribute já existente. Escolha outro.',
	    'username.required' => 'O username tem que ser preenchido.',
	    'username.min' => 'O username tem que ter pelo menos 4 letras ou dígitos.',
	    'email.email' => 'O email tem que ser válido.',
	    'email.required' => 'O email tem que ser preenchido.',
	    'name.required' => 'O nome tem que ser preenchido.',
	    'name.min' => 'O nome tem que ter pelo menos 4 letras.',
	    'location.required_if' => 'A localização tem que ser preenchida.',
	    'location.min' => 'A localização tem que ter pelo menos 4 letras.',
	    'facility.required_if' => 'O local de trabalho tem que ser preenchido.',
	    'facility.min' => 'O local de trabalho tem que ter pelo menos 4 letras.',
	    'job.required_if' => 'A profissão tem que ser preenchida.',
	    'job.min' => 'A profissão tem que ter pelo menos 4 letras.',
	    'password.required' => 'A password tem que ser preenchida.',
	    'password.min' => 'A password tem que ter pelo menos 6 letras ou digitos.',
	    'confirmed' => 'As passwords têm que ser iguais nos dois campos.',
	]; 

	public function dashboard()
	{
		if (Auth::guest()) {
			return view('auth.login');
		}
		
		if (Auth::user()->blocked == 1) {
            Auth::logout();
           	Session::flash('blockedAccount', "A sua conta foi bloqueada.");
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
			$canExport = false;
			$myCaregivers = Auth::user()->caregivers;
			if (count($myCaregivers) > 0) {
				foreach ($myCaregivers as $myCaregiver) {
					if (count($myCaregiver->accesses) > 0) {
						$canExport = true;
						break;
					}
				}
			}

			$caregivers = Auth::user()->caregivers()->paginate(10, ['*'], 'caregivers');
			$caregivers->setPageName('caregivers');

			$otherCaregivers = Caregiver::whereNotIn('id', Auth::user()->caregivers->modelKeys())->paginate(10, ['*'], 'otherCaregivers');
			foreach ($otherCaregivers as $index => $otherCaregiver) {
				if (count($otherCaregiver->healthcarePros) >= 2) {
					$otherCaregivers->forget($index);
				}
			}
			$otherCaregivers->setPageName('otherCaregivers');

			return view('dashboard.healthcarepro', compact('caregivers', 'otherCaregivers', 'canExport'));
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
        $searchData = ['userName' => '', 'userEmail' => '', 'userRole' => '', 'userCaregivers' => '', 'userSort' => '', 'userPages' => '', 'userBlocked' => ''];

		if (Auth::user()->role == 'healthcarepro') {
			$request->session()->put('userRole', 'caregiver');
		}

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

		if (!empty($searchData['userName'])) {
           	$where[] = ['name', 'like', '%'.$searchData['userName'].'%'];
        }

        if (!empty($searchData['userEmail'])) {
        	$where[] = ['email', 'like', '%'.$searchData['userEmail'].'%'];
        }

		if (!empty($searchData['userRole'])) {
			if($searchData['userRole'] != 'all') {
                $where[] = ['role', 'like', '%'.$searchData['userRole'].'%'];
            }
        }

		if (!empty($searchData['userBlocked'])) {
            if($searchData['userBlocked'] == 'just_blocked') {
                $where[] = ['blocked', 1];
            } elseif($searchData['userBlocked'] == 'just_unblocked') {
                $where[] = ['blocked', 0];
            }
        }

		if (!empty($searchData['userSort'])) {
            if($searchData['userSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['userSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['userSort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['userSort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            } elseif($searchData['userSort'] == 'email_az') {
                $col = 'email';
                $order = 'asc';
            } elseif($searchData['userSort'] == 'email_za') {
                $col = 'email';
                $order = 'desc';
            } elseif($searchData['userSort'] == 'role_az') {
                $col = 'role';
                $order = 'asc';
            } elseif($searchData['userSort'] == 'role_za') {
                $col = 'role';
                $order = 'desc';
            }
        }

		if (!empty($searchData['userPages'])) {
            $pages = $searchData['userPages'];
        }

		if (!empty($searchData['userCaregivers'])) {
			if ($searchData['userCaregivers'] == 'mine') {
				$users = Auth::user()->caregivers()->where($where)->orderBy($col, $order)->paginate((int)$pages);
				foreach ($users as $user) {
					$this->roleToFullWord($user);
				}

				return view('users.index', compact('users','searchData'));
			}
        }

		$users = User::where($where)->orderBy($col, $order)->paginate((int)$pages);
		foreach ($users as $user) {
			$this->roleToFullWord($user);
		}

		return view('users.index', compact('users','searchData'));
	}	

	public function show(User $user)
	{
		if (Auth::user()->role == 'healthcarepro' && $user->role != 'caregiver') {
			abort(403);
		}

		$this->roleToFullWord($user);

		$isMyCaregiver = false;
		$healthcarepros = $user->healthcarePros;
		if (Auth::user()->role == 'healthcarepro' && $healthcarepros != null) {
			if ($healthcarepros->contains('id', Auth::user()->id)) {
				$isMyCaregiver = true;
			}
		}

		$logs = $user->logs()->paginate(10, ['*'], 'logs');
		$logs->setPageName('logs');

		return view('users.show', compact('user', 'isMyCaregiver', 'logs'));
	}

	public function showProfile(User $user)
	{
		$this->roleToFullWord($user);

		return view('users.profile', compact('user'));
	}

	public function create($role)
	{	
		if (Auth::user()->role == 'healthcarepro' && $role != 'caregiver') {
			abort(403);
		}

		return view('users.create', compact('role'));
	}

	public function store(Request $request)
	{	
		if (Auth::user()->role == 'healthcarepro' && $request->input('role') != 'caregiver') {
			abort(403);
		}

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

		if (Auth::user()->role == 'healthcarepro') {
			Auth::user()->caregivers()->attach(User::where('username', $user->username)->firstOrFail()->id);
		}

		$this->roleToFullWord($user);

		$log = new Log();
		$log->performed_task = 'Foi criado.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $user->id;
		$log->save(); 

		return redirect()->route('users');
	}

	public function edit(User $user) {
		if (Auth::user()->role == 'healthcarepro' && $user->role != 'caregiver') {
			abort(403);
		}

		$this->roleToFullWord($user);
		return view('users.edit', compact('user'));
	}

	public function update(Request $request, User $user)
	{
		if (Auth::user()->role == 'healthcarepro' && $user->role != 'caregiver') {
			abort(403);
		}

		$this->validate($request, [
			'name' => 'required|min:4',
			'email' => 'required|email|unique:users,email,'.$user->id,
			'job' => 'nullable|min:4|required_if:role,Profissional de Saúde',
			'facility' => 'nullable|min:4|required_if:role,Profissional de Saúde',
			'location' => 'nullable|min:4|required_if:role,Cuidador',
		], $this->messages);

		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->job = $request->input('job');
		$user->facility = $request->input('facility');
		$user->location = $request->input('location');

		if ($request->input('password')) {
			$this->validate($request, [
				'password' => 'required|confirmed|min:6',
			], $this->messages);

			$user->password = bcrypt($request->input('password'));
		}

		$user->save();

		$this->roleToFullWord($user);

		$log = new Log();
		$log->performed_task = 'Foi atualizado.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $user->id;
		$log->save();

		return redirect()->route('users');
	}
	
	public function toggleBlock(Request $request, User $user)
	{
		if (Auth::user()->role == 'healthcarepro' && $user->role != 'caregiver') {
			abort(403);
		}

		if ($user->blocked == 0) {
            $user->blocked = 1;
            $user->save();

			$this->roleToFullWord($user);
			$log = new Log();
			$log->performed_task = 'Foi bloqueado.';
			$log->done_by = Auth::user()->id;
			$log->user_id = $user->id;
			$log->save();

            $request->session()->flash('blockedStatus', "$user->role $user->username foi bloqueado.");
        } elseif ($user->blocked == 1) {
            $user->blocked = 0;
            $user->save();

			$this->roleToFullWord($user);
			$log = new Log();
			$log->performed_task = 'Foi desbloqueado.';
			$log->done_by = Auth::user()->id;
			$log->user_id = $user->id;
			$log->save();

            $request->session()->flash('blockedStatus', "$user->role $user->username foi desbloqueado.");
        }

        return back();
	}

	public function associate(User $user, Caregiver $caregiver)
    {
		if (count($caregiver->healthcarePros) >= 2 || $caregiver->healthcarePros->contains('id', $user->id)) {
			abort(403);
		}
		$user->caregivers()->attach($caregiver->id);

		$this->roleToFullWord($user);
		$this->roleToFullWord($caregiver);

		$log = new Log();
		$log->performed_task = 'Foi associado ao '.$user->role.': '.$user->username.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $caregiver->id;
		$log->save();

		$log = new Log();
		$log->performed_task = 'Tornou-se responsável pelo '.$caregiver->role.': '.$caregiver->username.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $user->id;
		$log->save();

        return redirect('/'); 
    }

    public function diassociate(User $user, Caregiver $caregiver)
    {
		if (count($caregiver->healthcarePros) <= 0 || !$caregiver->healthcarePros->contains('id', $user->id)) {
        	abort(403);
		}
		$user->caregivers()->detach($caregiver->id);

		$this->roleToFullWord($user);
		$this->roleToFullWord($caregiver);

		$log = new Log();
		$log->performed_task = 'Foi desassociado ao '.$user->role.': '.$user->username.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $caregiver->id;
		$log->save();

		$log = new Log();
		$log->performed_task = 'Deixou de ser responsável pelo '.$caregiver->role.': '.$caregiver->username.'.';
		$log->done_by = Auth::user()->id;
		$log->user_id = $user->id;
		$log->save();

        return redirect('/'); 
    }

	public function notifications(User $user)
    {
		$allNotifications = collect();
		foreach ($user->caregivers as $caregiver) {
			$allNotifications = $allNotifications->merge($caregiver->notificationsCreated);
		}
		$sortedNotifications = $allNotifications->sortByDesc(function($notification) {
			return $notification->created_at;
		});

		$notifications = collect();
		foreach ($sortedNotifications as $notification) {
			$replica = $notification->replicate();
			$replica->created_at = $notification->created_at;
			$notifications->push($replica);
			if ($notification->viewed == 0) {
				$notification->viewed = 1;
				$notification->save();
			}
		}

        return view('notifications.index', ['user' => Auth::user()->id], compact('notifications'));
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

	public static function changeTypeFormat($materials)
	{
		foreach ($materials as $material) {
			MaterialsController::changeTypeFormat($material);
		}
	}

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('userName', $request->input('userName'));
        $request->session()->put('userEmail', $request->input('userEmail'));
		if (Auth::user()->role == 'admin') {
        	$request->session()->put('userRole', $request->input('userRole'));
		}
		$request->session()->put('userCaregivers', $request->input('userCaregivers'));
		$request->session()->put('userSort', $request->input('userSort'));
        $request->session()->put('userPages', $request->input('userPages'));
        $request->session()->put('userBlocked', $request->input('userBlocked'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['userName'] = $request->session()->get('userName');
        $searchData['userEmail'] = $request->session()->get('userEmail');
        $searchData['userRole'] = $request->session()->get('userRole');
		$searchData['userCaregivers'] = $request->session()->get('userCaregivers');
        $searchData['userSort'] = $request->session()->get('userSort');
        $searchData['userPages'] = $request->session()->get('userPages');
		$searchData['userBlocked'] = $request->session()->get('userBlocked');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('userName') && !$request->has('userEmail')
            && !$request->has('userRole') && !$request->has('userCaregivers')
			&& !$request->has('userSort') && !$request->has('userPages') 
			&& !$request->has('userBlocked')) {
            return true;
        }
        return false;
    }
}
