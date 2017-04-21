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

	public function patients()
	{
		$patients = Patient::all();

		return view('patients.patients', compact('patients'));
	}

    public function createPatient()
	{	
		$patient = new Patient();

		return view('patients.create_patient', compact('patient'));
	}

	public function savePatient(Request $request)
	{

		$this->validate($request, [
				'name' => 'required',
				'email' => 'email|required|unique:patients', 
				'location' => 'required',
			], $this->messages);

		$patient = new Patient($request->all());
		$patient->created_by = Auth::user()->id;
		$patient->save();

		return redirect('/patients');
	}


	
	public function patientNeeds($id)
	{
		
		$needs = Patient::find($id)->needs;

		return view('patients.patient_needs', compact('needs'));
	}

	public function updatePatient($id)
	{	
		$updatePatient =  Patient::find($id);
		
		return view('patients.update_patient', compact('updatePatient'));
	}

	public function update(Request $request)
	{
		$this->validate($request, [
				'name' => 'required',
				'email' => [
						'required', 'email' , Rule::unique('users')->ignore($request->id, 'id'),
						],
				'location' => 'required',
			], $this->messages);

		$patient = Patient::find($request->id);
		$patient->name = $request->input('name');
		$patient->email = $request->input('email');
		$patient->location = $request->input('location');

		$patient->save();

		return redirect('/patients');
	}

}
