<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use Auth;

class QuestionController extends Controller
{
    private $messages = [
	    'question.required' => 'A pergunta tem que ser preenchida.',
	    'question.min' => 'A pergunta tem que ter um tamanho de pelo menos 8 letras.',
	];

	public function index()
    {
    	$questions = Question::all();

    	return view('questions.index', compact('questions'));
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

		return redirect()->route('questions');
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

		return redirect()->route('questions');
    }

    public function delete(Question $question)
    {
    	$question->delete();

    	return redirect()->route('questions');
    }
}
