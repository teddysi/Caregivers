<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use App\Log;
use Auth;
use DB;

class QuestionsController extends Controller
{

    private $messages = [
	    'question.required' => 'A pergunta tem que ser preenchida.',
	    'question.min' => 'A pergunta tem que ter um tamanho de pelo menos 8 letras.',
        'values.required_if' => 'Tem que preencher o campo "Opções" com respostas.',
        'conice' => 'merda',
	];

	public function index(Request $request)
    {
    	$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['question' => '', 'questionCreator' => '', 'questionSort' => '', 'questionPages' => ''];

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

		if (!empty($searchData['question'])) {
           	$where[] = ['question', 'like', '%'.$searchData['question'].'%'];
        }

        if (!empty($searchData['questionCreator'])) {
			$user = User::where('username','like','%'.$searchData['questionCreator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['questionSort'])) {
            if($searchData['questionSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['questionSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['questionSort'] == 'question_az') {
                $col = 'question';
                $order = 'asc';
            } elseif($searchData['questionSort'] == 'question_za') {
                $col = 'question';
                $order = 'desc';
            }
        }

		if (!empty($searchData['questionPages'])) {
            $pages = $searchData['questionPages'];
        }

		$questions = Question::where($where)->orderBy($col, $order)->paginate((int)$pages);
        foreach ($questions as $question) {
			if (count($question->quizs) < 1) {
				$question->canBeBlocked = true;
			} else {
				$question->canBeBlocked = false;
			}
		}

		return view('questions.index', compact('questions', 'searchData'));
    }

    public function create()
    {
    	return view('questions.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
				'question' => 'required|min:4',
                'values' => 'required_if:type,radio',
		], $this->messages);

        $errors = $validator->errors();
        
		$question = new Question();
		$question->question = $request->input('question');
		$question->created_by = Auth::user()->id;

        if ($request->input('selectType') == 'radio') {
            $question->type = 'radio';
            $values = $request->input('values');
            $errors = $this->validateOptions($values, $errors);
            $question->values = $values;
        } else {
            $question->type = 'text';
        }

        if (!$errors->isEmpty()) {
            return back()->withErrors($errors)->withInput();
        }

		$question->save();

		return redirect()->route('questions');
    }

    public function validateOptions($values , $errors)
    {
        $count_values = substr_count($values,";");

        if (substr($values, 0, 1) === ';') {
            $errors->add('values','O campo "Opções" não pode começar com ";".');
        }

        if (substr($values, -1) !== ';') {
            $errors->add('values','O campo "Opções" tem que terminar com ";".');
        }

        if (strpos($values, ' ;')) {
            $errors->add('values', 'O campo "Opções" não deve conter espaços em branco antes de ";".');
        }

        if ($count_values < 2) {
            $errors->add('values', 'O campo "Opções" tem que ter pelo menos duas respostas.');
        }

        return $errors;
    }

    public function show(Question $question)
    {
        if (count($question->quizs) < 1) {
            $question->canBeBlocked = true;
        } else {
            $question->canBeBlocked = false;
        }

        if ($question->type == 'radio') {
            $values = explode(";", $question->values);
            array_pop($values);
            
            return view('questions.show', compact('question', 'values'));
        }

    	return view('questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        if (count($question->quizs) == 0) {
            if($question->type == 'radio') {
                $values = $question->values;
                return view('questions.edit', compact('question', 'values'));
            }

    	   return view('questions.edit', compact('question'));
        }
    }

    public function update(Request $request, Question $question)
    {
    	$validator = \Validator::make($request->all(), [
                'question' => 'required|min:4',
                'values' => 'required_if:type,radio',
        ], $this->messages);

        $errors = $validator->errors();
		$question->question = $request->input('question');

        if ($question->type == 'radio') {
            $values = $request->input('values');
            $errors = $this->validateOptions($values, $errors);
            $question->values = $values;
        }

        if (!$errors->isEmpty()) {
            return back()->withErrors($errors)->withInput();
        }
		$question->save();

		return redirect()->route('questions');
    }

    public function toggleBlock(Request $request, Question $question)
    {
		if ($question->blocked == 0) {
            $question->blocked = 1;
            $question->save();

			$log = new Log();
			$log->performed_task = 'Foi bloqueada.';
			$log->done_by = Auth::user()->id;
			$log->question_id = $question->id;
			$log->save();

            $request->session()->flash('blockedStatus', "A Questão $question->question foi bloqueada.");
        } elseif ($question->blocked == 1) {
            $question->blocked = 0;
            $question->save();

			$log = new Log();
			$log->performed_task = 'Foi desbloqueada.';
			$log->done_by = Auth::user()->id;
			$log->question_id = $question->id;
			$log->save();

            $request->session()->flash('blockedStatus', "A Questão $question->question foi desbloqueada.");
        }

        return back();
    }

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('question', $request->input('question'));
        $request->session()->put('questionCreator', $request->input('questionCreator'));
		$request->session()->put('questionSort', $request->input('questionSort'));
        $request->session()->put('questionPages', $request->input('questionPages'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['question'] = $request->session()->get('question');
        $searchData['questionCreator'] = $request->session()->get('questionCreator');
        $searchData['questionSort'] = $request->session()->get('questionSort');
        $searchData['questionPages'] = $request->session()->get('questionPages');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('question') && !$request->has('questionCreator') 
			&& !$request->has('questionSort') && !$request->has('questionPages')) {
            return true;
        }
        return false;
    }

}
