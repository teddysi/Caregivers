<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use Auth;
use DB;

class QuestionsController extends Controller
{
    private $messages = [
	    'question.required' => 'A pergunta tem que ser preenchida.',
	    'question.min' => 'A pergunta tem que ter um tamanho de pelo menos 8 letras.',
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

		return view('questions.index', compact('questions', 'searchData'));
    }

    public function create()
    {
    	return view('questions.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
				'question' => 'required|min:4',
		], $this->messages);

		$question = new Question();
		$question->question = $request->input('question');
		$question->created_by = Auth::user()->id;

		$question->save();

		return redirect('/');
    }

    public function show(Question $question)
    {
    	return view('questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
    	return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
    	$this->validate($request, [
				'question' => 'required|min:4',
		], $this->messages);

		$question->question = $request->input('question');
		$question->save();

		return redirect('/');
    }

    public function delete(Question $question)
    {
		$quizs = $question->quizs;
		foreach ($quizs as $quiz) {
			$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;
			$quiz->questions()->detach($question->id);

			$questionsToUpdateOrder = $quiz->questions()->where('order', '>', $orderOfQuestion)->get();
			foreach ($questionsToUpdateOrder as $questionToUpdate) {
				$orderOfQuestionToUpdate = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $questionToUpdate->id]])->first()->order;
				$quiz->questions()->updateExistingPivot($questionToUpdate->id, ['order' => $orderOfQuestionToUpdate - 1]);
			}
		}

		Answer::where('question_id', $question->id)->delete();
    	$question->delete();

    	return redirect('/');
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
