<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Patient;
use Auth;


class PatientsController extends Controller
{
    private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

    public function create()
	{	
		return view('patients.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'email|required|unique:patients', 
			'location' => 'required',
		], $this->messages);

		$patient = new Patient();
		$patient->name = $request->input('name');
		$patient->email = $request->input('email');
		$patient->location = $request->input('location');
		$patient->created_by = Auth::user()->id;
		$patient->save();

		return redirect('/');
	}

	public function show(Patient $patient)
	{
		return view('patients.show', compact('patient'));
	}

	public function edit(Patient $patient) {
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

		return redirect('/');
	}

	public function needs(Patient $patient)
    {
        $needs = $patient->needs()->paginate(10);

        return view('patients.needs',  compact('patient', 'needs'));   
    }
}
