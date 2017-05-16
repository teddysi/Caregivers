<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evaluation;
use App\Log;
use Auth;
use Storage;
Use Response;

class EvaluationsController extends Controller
{
	private $messages = [
	    'description.required' => 'A descrição tem que ser preenchida.',
	    'description.min' => 'A descrição tem que ter pelo menos 4 letras.',
	    'type.required' => 'O tipo de avaliação tem que ser preenchido',
	   	'type.min' => 'O tipo de avaliação tem que ter pelo menos 4 letras',
	   	'model.required' => 'O modelo tem que ser preenchido',
	   	'model.min' => 'O modelo tem que ter pelo menos 3 letras',
	    'path.required' => 'Introduza um ficheiro de avaliação.',
	];

	public function show(Evaluation $evaluation)
	{
		if ($evaluation->caregiver_id != null) {
			if (!Auth::user()->caregivers->contains('id', $evaluation->caregiver_id)) {
				abort(403);
			}
		}

		return view('evaluations.show', compact('evaluation'));
	}

    public function create($id)
	{	
		if (!str_contains(url()->current(), '/patients/')) {
			if (!Auth::user()->caregivers->contains('id', $id)) {
				abort(403);
			}
		}

		return view('evaluations.create', compact('id'));
	}

	public function store(Request $request, $id)
	{
		$this->validate($request, [
				'description' => 'required|min:4',
				'type' => 'required|min:4',
				'model' => 'required|min:3',
				'path' => 'required',
				'mime' => 'nullable',
		], $this->messages);

		$evaluation = new Evaluation();
		$evaluation->description = $request->input('description');
		$evaluation->type = $request->input('type');
		$evaluation->model = $request->input('model');

		$originalName = $request->path->getClientOriginalName();
		$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
		$evaluation->path = $originalName;
		$evaluation->mime = '.' . $whatIWant;
		$evaluation->created_by = Auth::user()->id;

		if (str_contains(url()->current(), '/patients/')) {
			$evaluation->patient_id = $id;
			$evaluation->save();

			return redirect()->route('patients.show', ['patient' => $id]);
		} else {
			if (!Auth::user()->caregivers->contains('id', $id)) {
				abort(403);
			}

			$evaluation->caregiver_id = $id;
			$evaluation->save();

			return redirect()->route('caregivers.rate', ['caregiver' => $id]);
		} 
	}

	public function edit(Evaluation $evaluation)
	{	
		if ($evaluation->caregiver_id != null) {
			if (!Auth::user()->caregivers->contains('id', $evaluation->caregiver_id)) {
				abort(403);
			}
		} 

		return view('evaluations.edit', compact('evaluation'));
	}

	public function update(Request $request, Evaluation $evaluation)
	{
		if ($evaluation->caregiver_id != null) {
			if (!Auth::user()->caregivers->contains('id', $evaluation->caregiver_id)) {
				abort(403);
			}
		} 

		$this->validate($request, [
				'description' => 'required|min:4',
				'type' => 'required|min:4',
				'model' => 'required|min:3',
		], $this->messages);

		$evaluation->description = $request->input('description');
		$evaluation->type = $request->input('type');
		$evaluation->model = $request->input('model');
		$evaluation->save();

		if ($evaluation->patient_id != null) {
			return redirect()->route('patients.show', ['patient' => $evaluation->patient_id]);
		} else if ($evaluation->caregiver_id != null) {
			return redirect()->route('caregivers.rate', ['caregiver' => $evaluation->caregiver_id]);
		} 
	}

	public function showEvaluation(Evaluation $evaluation)
	{
		if ($evaluation->caregiver_id != null) {
			if (!Auth::user()->caregivers->contains('id', $evaluation->caregiver_id)) {
				abort(403);
			}
		} 

		$content = Storage::get($evaluation->path);
		$whatIWant = substr($evaluation->path, strpos($evaluation->path, ".") + 1);
		$contentType = 'application/'.$whatIWant;

		if ($whatIWant != 'pdf') {
			$file = storage_path('app/'.$evaluation->path);
			return Response::download($file, $evaluation->name.$evaluation->mime, ['Content-Type: application/'.$whatIWant]);
		}

		return response($content)->header('Content-Type', $contentType);
	}
}
