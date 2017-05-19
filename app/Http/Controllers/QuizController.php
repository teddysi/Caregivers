<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Question;
use Auth;
use DB;

class QuizController extends Controller
{
	private $messages = [
	    'name.required' => 'O nome tem que ser preenchido.',
	    'name.min' => 'O nome tem que ter pelo menos 4 letras.',
	];

    public function index()
    {
    	$quizs = Quiz::all();

    	return view('quizs.index', compact('quizs'));
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

		return redirect()->route('quizs');
    }

    public function delete(Quiz $quiz)
    {

    	$quiz->questions()->detach();

    	$quiz->delete();

    	return redirect()->route('quizs');
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

	public function show(Quiz $quiz) 
	{
		$quizQuestions = $quiz->questions()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'quizQuestions');

		return view('quizs.show', compact('quizQuestions', 'quiz'));
	}
}
