<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evaluation;
use Auth;

class EvaluationsController extends Controller
{

	private $messages = [
	    'unique' =>  ':attribute já existe. Escolha outro.',
	    'required' => ':attribute tem que ser preenchido.',
	];

    public function create($id)
	{	
		return view('evaluations.create', compact('id'));
	}

	public function store(Request $request, $id)
	{
		$this->validate($request, [
				'name' => 'required|min:4|unique:evaluations',
				'description' => 'required|min:4',
				'path' => 'nullable',
				'url' => 'nullable|url',
				'mime' => 'nullable',
		], $this->messages);

		$evaluation = new Evaluation();
		$evaluation->name = $request->input('name');
		$evaluation->description = $request->input('description');

		$originalName = $request->path->getClientOriginalName();
		$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
		$evaluation->path = $request->file('path')->storeAs('evaluations', $request->input('name') . '.' . $whatIWant);
		$evaluation->mime = '.' . $whatIWant;
		
		$evaluation->created_by = Auth::user()->id;

		if (str_contains(url()->current(), '/patients/')) {
			$evaluation->patient_id = $id;
		} else {
			$evaluation->caregiver_id = $id;
		}


		$evaluation->save();

		return redirect('/');
	}
}
