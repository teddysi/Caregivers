<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Question;
use App\Answer;
use Auth;
use DB;

class QuizsController extends Controller
{
	private $messages = [
	    'name.required' => 'O nome tem que ser preenchido.',
	    'name.min' => 'O nome tem que ter pelo menos 4 letras.',
	];

    public function index(Request $request)
	{
		$where = [];
        $pages = '10';
        $col = 'created_at';
        $order = 'desc';
        $searchData = ['quizName' => '', 'quizCreator' => '', 'quizSort' => '', 'quizPages' => ''];

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

		if (!empty($searchData['quizName'])) {
           	$where[] = ['name', 'like', '%'.$searchData['quizName'].'%'];
        }

        if (!empty($searchData['quizCreator'])) {
			$user = User::where('username','like','%'.$searchData['quizCreator'].'%')->first();
           	$where[] = ['created_by', $user->id];
        }

		if (!empty($searchData['quizSort'])) {
            if($searchData['quizSort'] == 'mrc') {
                $col = 'created_at';
                $order = 'desc';
            } elseif($searchData['quizSort'] == 'lrc') {
                $col = 'created_at';
                $order = 'asc';
            } elseif($searchData['quizSort'] == 'name_az') {
                $col = 'name';
                $order = 'asc';
            } elseif($searchData['quizSort'] == 'name_za') {
                $col = 'name';
                $order = 'desc';
            }
        }

		if (!empty($searchData['quizPages'])) {
            $pages = $searchData['quizPages'];
        }

		$quizs = Quiz::where($where)->orderBy($col, $order)->paginate((int)$pages);

		return view('quizs.index', compact('quizs', 'searchData'));
	}

    public function create()
    {
    	return view('quizs.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
				'name' => 'required|min:4',
		], $this->messages);

		$quiz = new Quiz();
		$quiz->name = $request->input('name');
		$quiz->created_by = Auth::user()->id;

		$quiz->save();

		return redirect()->route('quizs.questions', ['quiz' => $quiz->id]);
    }

	public function show(Quiz $quiz) 
	{
		$quizQuestions = $quiz->questions()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'quizQuestions');

		return view('quizs.show', compact('quiz', 'quizQuestions'));
	}

	public function edit(Quiz $quiz)
    {
    	return view('quizs.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
    	$this->validate($request, [
				'name' => 'required|min:4',
		], $this->messages);

		$quiz->name = $request->input('name');
		$quiz->save();

		return redirect('/');
    }

	public function delete(Quiz $quiz)
    {
		//TODO: apagar avaliaçoes k usem este questionario
        Answer::where('quiz_id', $quiz->id)->delete();
    	$quiz->questions()->detach();
    	$quiz->delete();

    	return redirect('/');
    }

	public function questions(Quiz $quiz)
	{
		$quizQuestions = $quiz->questions()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'quizQuestions');
		$quizQuestions->setPageName('quizQuestions');

		$notQuizQuestions = Question::whereNotIn('id', $quiz->questions->modelKeys())
									->paginate(10, ['*'], 'notQuizQuestions');
		$notQuizQuestions->setPageName('notQuizQuestions');

		return view('quizs.questions', compact('quiz', 'quizQuestions', 'notQuizQuestions'));
	}

    public function addQuestion(Quiz $quiz, Question $question)
    {
    	$count = count($quiz->questions()->get());
		$quiz->questions()->attach([$question->id => ['order'=> $count + 1]]);

		return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
    }

    public function removeQuestion(Quiz $quiz, Question $question)
	{
		$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;
		$quiz->questions()->detach($question->id);

		$questionsToUpdateOrder = $quiz->questions()->where('order', '>', $orderOfQuestion)->get();
		foreach ($questionsToUpdateOrder as $questionToUpdate) {

			$orderOfQuestionToUpdate = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $questionToUpdate->id]])->first()->order;

			$quiz->questions()->updateExistingPivot($questionToUpdate->id, ['order' => $orderOfQuestionToUpdate - 1]);
		}

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	public function upQuestion(Quiz $quiz, Question $question)
	{
		$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;

		$aboveQuestion = $quiz->questions()->where('order', $orderOfQuestion - 1)->first();

		$quiz->questions()->updateExistingPivot($question->id, ['order' => $orderOfQuestion - 1]);
		$quiz->questions()->updateExistingPivot($aboveQuestion->id, ['order' => $orderOfQuestion]);

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	public function downQuestion(Quiz $quiz, Question $question)
	{
		$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;
		$aboveQuestion = $quiz->questions()->where('order', $orderOfQuestion + 1)->first();
		$quiz->questions()->updateExistingPivot($question->id, ['order' => $orderOfQuestion + 1]);
		$quiz->questions()->updateExistingPivot($aboveQuestion->id, ['order' => $orderOfQuestion]);

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('quizName', $request->input('quizName'));
        $request->session()->put('quizCreator', $request->input('quizCreator'));
		$request->session()->put('quizSort', $request->input('quizSort'));
        $request->session()->put('quizPages', $request->input('quizPages'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['quizName'] = $request->session()->get('quizName');
        $searchData['quizCreator'] = $request->session()->get('quizCreator');
        $searchData['quizSort'] = $request->session()->get('quizSort');
        $searchData['quizPages'] = $request->session()->get('quizPages');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('quizName') && !$request->has('quizCreator') 
			&& !$request->has('quizSort') && !$request->has('quizPages')) {
            return true;
        }
        return false;
    }
}
