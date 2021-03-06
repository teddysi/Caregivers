<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evaluation;
use App\Log;
use App\Quiz;
use App\Caregiver;
use App\Patient;
use App\Material;
use App\Notification;
use Auth;
use Storage;
use Response;
use DB;

class EvaluationsController extends Controller
{
	private $messages = [
	    'description.required' => 'A descrição tem que ser preenchida.',
	    'description.min' => 'A descrição tem que ter pelo menos 4 letras.',
	    'type.required' => 'O tipo de avaliação tem que ser preenchido',
	   	'type.min' => 'O tipo de avaliação tem que ter pelo menos 4 letras',
	   	'model.required' => 'O modelo tem que ser preenchido',
	   	'model.required_if' => 'O modelo tem que ser preenchido',
	   	'model.min' => 'O modelo tem que ter pelo menos 3 letras',
	    'path.required_if' => 'Introduza um ficheiro de avaliação.',
	];

	public function show(Evaluation $evaluation)
	{
		if ($evaluation->caregiver_id != null) {
			if (!Auth::user()->caregivers->contains('id', $evaluation->caregiver_id)) {
				abort(403);
			}
		}

		$logs = $evaluation->logs()->paginate(10, ['*'], 'logs');
		$logs->setPageName('logs');

		return view('evaluations.show', compact('evaluation', 'logs'));
	}

    public function create($id, $typeEval)
	{	
		if (!str_contains(url()->current(), '/patients/')) {
			if (!Auth::user()->caregivers->contains('id', $id)) {
				abort(403);
			}
		}
		if($typeEval == 'quiz') {
			if (str_contains(url()->current(), '/patients/')) {
				$patient = Patient::find($id);
				$quizs = Quiz::whereNotIn('id', $patient->quizs->modelKeys())
								->where('blocked', 0)
								->get();
				
				return view('evaluations.create_quiz', compact('patient', 'quizs', 'typeEval'));	
			} else {
				$caregiver = Caregiver::find($id);
				$quizs = Quiz::whereNotIn('id', $caregiver->quizs->modelKeys())
								->where('blocked', 0)
								->get();
				
				return view('evaluations.create_quiz', compact('caregiver', 'quizs', 'typeEval'));
			}
		}

		return view('evaluations.create', compact('id', 'typeEval'));
	}

	public function store(Request $request, $id)
	{
		$this->validate($request, [
				'description' => 'required|min:4',
				'type' => 'required|min:4',
				'model' => 'required_if:typeEval,eval|min:3',
				'path' => 'required_if:typeEval,eval',
				'mime' => 'nullable',
		], $this->messages);
		
		$evaluation = new Evaluation();
		$evaluation->description = $request->input('description');
		$evaluation->type = $request->input('type');

		if($request->input('typeEval') == 'eval') {
			$evaluation->model = $request->input('model');

			$originalName = $request->path->getClientOriginalName();
			$whatIWant = substr($originalName, strpos($originalName, ".") + 1);
			$evaluation->path = $request->file('path')->storeAs('evaluations', $request->input('description').'.'.$whatIWant);
			$evaluation->mime = '.' . $whatIWant;
		}

		$evaluation->created_by = Auth::user()->id;
		
		$patient = Patient::find($id);
		if (str_contains(url()->current(), '/patients/')) {
			if($request->input('typeEval') == 'quiz') {
				$quiz = Quiz::find($request->input('quiz'));
				$evaluation->model = 'Questionário '.$quiz->name;
				$evaluation->answered_by = $patient->caregiver->id;
				$evaluation->patient_id = $id;
				$evaluation->save();
				$quiz->patients()->attach([$id => ['evaluation_id'=> $evaluation->id]]);
			} else {
				$evaluation->patient_id = $id;
				$evaluation->save();

				$fileExists = Storage::exists('evaluations/'.$evaluation->id.$evaluation->mime);
				if ($fileExists) {
					Storage::delete('evaluations/'.$evaluation->id.$evaluation->mime);
				}

				Storage::move($evaluation->path, 'evaluations/'.$evaluation->id.$evaluation->mime);
				$evaluation->path = 'evaluations/'.$evaluation->id.$evaluation->mime;
				$evaluation->save();
			}

			$log = new Log();
			$log->performed_task = 'Foi criada.';
			$log->done_by = Auth::user()->id;
			$log->evaluation_id = $evaluation->id;
			$log->save();

			return redirect()->route('patients.show', ['patient' => $id]);
		} else {
			if (!Auth::user()->caregivers->contains('id', $id)) {
				abort(403);
			}

			if($request->input('typeEval') == 'quiz') {
				$quiz = Quiz::find($request->input('quiz'));
				$evaluation->model = 'Questionário '.$quiz->name;
				$evaluation->answered_by = $id;
				$evaluation->caregiver_id = $id;
				$evaluation->save();
				$quiz->caregivers()->attach([$id => ['evaluation_id'=> $evaluation->id]]);
			} else {
				$evaluation->caregiver_id = $id;
				$evaluation->save();

				$fileExists = Storage::exists('evaluations/'.$evaluation->id.$evaluation->mime);
				if ($fileExists) {
					Storage::delete('evaluations/'.$evaluation->id.$evaluation->mime);
				}

				Storage::move($evaluation->path, 'evaluations/'.$evaluation->id.$evaluation->mime);
				$evaluation->path = 'evaluations/'.$evaluation->id.$evaluation->mime;
				$evaluation->save();
			}

			$log = new Log();
			$log->performed_task = 'Foi criada.';
			$log->done_by = Auth::user()->id;
			$log->evaluation_id = $evaluation->id;
			$log->save();

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

		$log = new Log();
		$log->performed_task = 'Foi atualizada.';
		$log->done_by = Auth::user()->id;
		$log->evaluation_id = $evaluation->id;
		$log->save();

		if ($evaluation->patient_id != null) {
			return redirect()->route('patients.show', ['patient' => $evaluation->patient_id]);
		} else if ($evaluation->caregiver_id != null) {
			return redirect()->route('caregivers.rate', ['caregiver' => $evaluation->caregiver_id]);
		} else if ($evaluation->material_id != null && $evaluation->answered_by != null) {
			return redirect()->route('materials.rate', ['caregiver' => $evaluation->answered_by, 'material' => $evaluation->material_id]);
		}  else if ($evaluation->material_id != null && $evaluation->submitted_by != null) {
			return redirect()->route('materials.rate', ['caregiver' => $evaluation->submitted_by, 'material' => $evaluation->material_id]);
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
			return Response::download($file, $evaluation->description.$evaluation->mime, ['Content-Type: application/'.$whatIWant]);
		}

		return response($content)->header('Content-Type', $contentType);
	}

	public function createForMaterial($id, Material $material)
	{
		if (!Auth::user()->caregivers->contains('id', $id)) {
			abort(403);
		}

		$quizs = Quiz::whereNotIn('id', $material->quizs($id)->get()->modelKeys())
						->where('blocked', 0)
						->get();

		return view('evaluations.create_quiz_material', compact('id', 'quizs', 'material'));
	}

	public function storeForMaterial(Request $request, Material $material)
	{
		$this->validate($request, [
			'description' => 'required|min:4',
			'type' => 'required|min:4',
		], $this->messages);

		$quiz = Quiz::find($request->input('quiz'));

		$evaluation = new Evaluation();
		$evaluation->description = $request->input('description');
		$evaluation->type = $request->input('type');
		$evaluation->created_by = Auth::user()->id;
		$evaluation->model = 'Questionário '.$quiz->name;
		$evaluation->material_id = $material->id;
		$evaluation->answered_by = $request->input('caregiver');
		$evaluation->save();

		$quiz->materials($request->input('caregiver'))->attach([$material->id => ['caregiver_id'=> $request->input('caregiver'), 'evaluation_id'=> $evaluation->id]]);

		$log = new Log();
		$log->performed_task = 'Foi criada.';
		$log->done_by = Auth::user()->id;
		$log->evaluation_id = $evaluation->id;
		$log->save();

		return redirect()->route('materials.rate', ['caregiver' => $request->input('caregiver'), 'material' => $material]);
	}

	public function createAPI(Request $request, $id) 
	{
	    $caregiverToken = $request->header('Authorization');
        $materialId = $request->input('material_id');
		$difficulty;
		switch ($request->input('evaluation')) {
			case '-1':
				$difficulty = 'Difícil';
				break;
			case '0':
				$difficulty = 'Médio';
				break;
			case '1':
				$difficulty = 'Fácil';
				break;
			default:
				break;
		}

        $caregiver = Caregiver::find($id);
        $material = Material::find($materialId);

        if ($caregiver == null || $material == null) {
            return response('Não Encontrado', 404);
        }

        /*if (!$caregiverToken || $caregiver->caregiver_token != $caregiverToken) {
            return response('Não Autorizado', 401);
        }*/

        $evaluation = new Evaluation;
        $evaluation->description = 'Dificuldade de Utilização';
        $evaluation->type = 'Através da App';
        $evaluation->model = 'Escala de Dificuldade';
		$evaluation->submitted_by = $id;
		$evaluation->difficulty = $difficulty;
		$evaluation->material_id = $materialId;
		$evaluation->created_by = $id;
        $evaluation->save();

		$log = new Log();
		$log->performed_task = 'Foi criada.';
		$log->done_by = $id;
		$log->evaluation_id = $evaluation->id;
		$log->save();

        $notification = new Notification();
        $notification->text = 'O Cuidador '.$caregiver->username.' classificou o Material '.$material->name.' com a dificuldade '.$difficulty.'.';
        $notification->created_by = $id;
        $notification->type = 'evaluation';
		$notification->evaluation_id = $evaluation->id;
        $notification->save();
        
        return response()->json("Evaluation submitted successfully");
	}
}
