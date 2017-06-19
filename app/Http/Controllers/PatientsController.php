<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Patient;
use App\Need;
use App\User;
use App\Log;
use Auth;

class PatientsController extends Controller
{
    private $messages = [
	    'unique' =>  ':Attribute já existente. Escolha outro.',
        'email.email' => 'O email tem que ser válido.',
	    'email.required' => 'O email tem que ser preenchido.',
        'name.required' => 'O nome tem que ser preenchido.',
        'name.min' => 'O nome tem que ter pelo menos 4 letras.',
        'location.required' => 'A localização tem que ser preenchida.',
        'location.min' => 'A localização tem que ter pelo menos 4 letras.',
	];

	public function index(Request $request)
	{
		$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['patientName' => '', 'patientEmail' => '', 'patientLocation' => '', 'patientCreator' => '', 'patientSort' => '', 'patientPages' => ''];

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

		if (!empty($searchData['patientName'])) {
           	$where[] = ['name', 'like', '%'.$searchData['patientName'].'%'];
        }

        if (!empty($searchData['patientEmail'])) {
        	$where[] = ['email', 'like', '%'.$searchData['patientEmail'].'%'];
        }

		if (!empty($searchData['patientLocation'])) {
           	$where[] = ['location', 'like', '%'.$searchData['patientLocation'].'%'];
        }

        if (!empty($searchData['patientCreator'])) {
			$user = User::where('username','like','%'.$searchData['patientCreator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['patientSort'])) {
            if($searchData['patientSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['patientSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['patientSort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['patientSort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            } elseif($searchData['patientSort'] == 'email_az') {
                $col = 'email';
                $order = 'asc';
            } elseif($searchData['patientSort'] == 'email_za') {
                $col = 'email';
                $order = 'desc';
            } elseif($searchData['patientSort'] == 'location_az') {
                $col = 'location';
                $order = 'asc';
            } elseif($searchData['patientSort'] == 'location_za') {
                $col = 'location';
                $order = 'desc';
            }
        }

		if (!empty($searchData['patientPages'])) {
            $pages = $searchData['patientPages'];
        }

		$patients = Patient::where($where)->orderBy($col, $order)->paginate((int)$pages);

		return view('patients.index', compact('patients','searchData'));
	}

    public function show(Patient $patient)
	{
        $evaluations = $patient->evaluations()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'evaluations');
		$evaluations->setPageName('evaluations');;

        $logs = $patient->logs()->paginate(10, ['*'], 'logs');
		$logs->setPageName('logs');
		return view('patients.show', compact('patient', 'evaluations', 'logs'));
	}

    public function create()
	{	
		return view('patients.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|min:4',
			'email' => 'email|required|unique:patients', 
			'location' => 'required|min:4',
		], $this->messages);

		$patient = new Patient();
		$patient->name = $request->input('name');
		$patient->email = $request->input('email');
		$patient->location = $request->input('location');
		$patient->created_by = Auth::user()->id;
		$patient->save();

        $log = new Log();
		$log->performed_task = 'Foi criado.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

		return redirect()->route('patients');
	}

	public function edit(Patient $patient)
    {
		return view('patients.edit', compact('patient'));
	}

	public function update(Request $request, Patient $patient)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:patients,email,'.$patient->id,
			'location' => 'required',
		], $this->messages);

		$patient->name = $request->input('name');
		$patient->email = $request->input('email');
		$patient->location = $request->input('location');
		$patient->save();

        $log = new Log();
		$log->performed_task = 'Foi atualizado.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

		return redirect()->route('patients');
	}

	public function needs(Patient $patient)
    {
        $needs = $patient->needs()->paginate(10, ['*'], 'needs');
		$needs->setPageName('needs');

		$notMyNeeds = Need::whereNotIn('id', $patient->needs->modelKeys())->paginate(10, ['*'], 'notMyNeeds');
		$notMyNeeds->setPageName('notMyNeeds');

        return view('patients.needs',  compact('patient', 'needs', 'notMyNeeds'));   
    }

	public function associate(Patient $patient, Need $need)
    {
        if ($patient->needs->contains('id', $need->id)) {
            abort(403);
        }
		$patient->needs()->attach($need->id);

        $caregiver = $patient->caregiver;
        foreach ($need->materials as $material) {
            if (!$caregiver->materials->contains("id", $material->id)) {
                $caregiver->materials()->attach($material->id);
            }
        }

        $log = new Log();
		$log->performed_task = 'Foi associada a Necessidade: '.$need->description.'.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

        $log = new Log();
		$log->performed_task = 'Foi associada ao Paciente: '.$patient->name.'.';
		$log->done_by = Auth::user()->id;
		$log->need_id = $need->id;
		$log->save();

        return redirect()->route('patients.needs', ['patient' => $patient->id]); 
    }

    public function diassociate(Patient $patient, Need $need)
    {
        if (!$patient->needs->contains('id', $need->id)) {
            abort(403);
        }
        $patient->needs()->detach($need->id);

        $log = new Log();
		$log->performed_task = 'Foi desassociada a Necessidade: '.$need->description.'.';
		$log->done_by = Auth::user()->id;
		$log->patient_id = $patient->id;
		$log->save();

        $log = new Log();
		$log->performed_task = 'Foi desassociada do Paciente: '.$patient->name.'.';
		$log->done_by = Auth::user()->id;
		$log->need_id = $need->id;
		$log->save();

        return redirect()->route('patients.needs', ['patient' => $patient->id]);
    }

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('patientName', $request->input('patientName'));
        $request->session()->put('patientEmail', $request->input('patientEmail'));
        $request->session()->put('patientLocation', $request->input('patientLocation'));
		$request->session()->put('patientCreator', $request->input('patientCreator'));
        $request->session()->put('patientSort', $request->input('patientSort'));
        $request->session()->put('patientPages', $request->input('patientPages'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['patientName'] = $request->session()->get('patientName');
        $searchData['patientEmail'] = $request->session()->get('patientEmail');
        $searchData['patientLocation'] = $request->session()->get('patientLocation');
        $searchData['patientCreator'] = $request->session()->get('patientCreator');
        $searchData['patientSort'] = $request->session()->get('patientSort');
		$searchData['patientPages'] = $request->session()->get('patientPages');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('patientName') && !$request->has('patientEmail')
            && !$request->has('patientLocation') && !$request->has('patientCreator')
            && !$request->has('patientSort') && !$request->has('patientPages')) {
            return true;
        }
        return false;
    }
}
