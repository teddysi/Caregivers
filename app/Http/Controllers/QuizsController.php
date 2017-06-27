<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Quiz;
use App\Question;
use App\Answer;
use App\Log;
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
        $searchData = ['quizName' => '', 'quizCreator' => '', 'quizSort' => '', 'quizPages' => '', 'quizBlocked' => ''];

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

		if (!empty($searchData['quizBlocked'])) {
            if($searchData['quizBlocked'] == 'just_blocked') {
                $where[] = ['blocked', 1];
            } elseif($searchData['quizBlocked'] == 'just_unblocked') {
                $where[] = ['blocked', 0];
            }
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
		foreach ($quizs as $quiz) {
			$count = 0;
			$count += count($quiz->caregivers);
			$count += count($quiz->patients);
			$count += DB::table('quiz_material')->where('quiz_id', $quiz->id)->count();

			if ($count < 1) {
				$quiz->canBeEditedOrBlocked = true;
			} else {
				$quiz->canBeEditedOrBlocked = false;
			}
		}

		return view('quizs.index', compact('quizs', 'searchData'));
	}

	public function show(Quiz $quiz) 
	{
		$quizQuestions = $quiz->questions()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'quizQuestions');

		$count = 0;
		$count += count($quiz->caregivers);
		$count += count($quiz->patients);
		$count += DB::table('quiz_material')->where('quiz_id', $quiz->id)->count();

		if ($count < 1) {
			$quiz->canBeEditedOrBlocked = true;
		} else {
			$quiz->canBeEditedOrBlocked = false;
		}

		$logs = $quiz->logs()->paginate(10, ['*'], 'logs');
		$logs->setPageName('logs');

		return view('quizs.show', compact('quiz', 'quizQuestions', 'logs'));
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

		$log = new Log();
		$log->performed_task = 'Foi criado.';
		$log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
		$log->save(); 

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

		$log = new Log();
		$log->performed_task = 'Foi atualizado.';
		$log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
		$log->save();

		return redirect()->route('quizs');
    }

	public function toggleBlock(Request $request, Quiz $quiz)
    {
		if ($quiz->blocked == 0) {
            $quiz->blocked = 1;
            $quiz->save();

			$log = new Log();
			$log->performed_task = 'Foi bloqueado.';
			$log->done_by = Auth::user()->id;
			$log->quiz_id = $quiz->id;
			$log->save();

            $request->session()->flash('blockedStatus', "O Questionário $quiz->name foi bloqueado.");
        } elseif ($quiz->blocked == 1) {
            $quiz->blocked = 0;
            $quiz->save();

			$log = new Log();
			$log->performed_task = 'Foi desbloqueado.';
			$log->done_by = Auth::user()->id;
			$log->quiz_id = $quiz->id;
			$log->save();

            $request->session()->flash('blockedStatus', "O Questionário $quiz->name foi desbloqueado.");
        }

        return back();
    }

	public function questions(Quiz $quiz)
	{
		$quizQuestions = $quiz->questions()->withPivot('order')->orderBy('pivot_order', 'asc')->paginate(10, ['*'], 'quizQuestions');
		$quizQuestions->setPageName('quizQuestions');

		$notQuizQuestions = Question::whereNotIn('id', $quiz->questions->modelKeys())
									->where('blocked', 0)
									->paginate(10, ['*'], 'notQuizQuestions');
		$notQuizQuestions->setPageName('notQuizQuestions');

		return view('quizs.questions', compact('quiz', 'quizQuestions', 'notQuizQuestions'));
	}

    public function addQuestion(Quiz $quiz, Question $question)
    {
		if ($question->blocked == 1) {
			abort(403);
		}
		
    	$count = count($quiz->questions);
		$quiz->questions()->attach([$question->id => ['order'=> $count + 1]]);

		$log = new Log();
        $log->performed_task = 'Foi adicionada a Questão: '.$question->question.' na posição '.($count + 1).'.';
		$log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
        $log->save();

		$log = new Log();
        $log->performed_task = 'Foi adicionada ao Questionário: '.$quiz->name.'.';
		$log->done_by = Auth::user()->id;
		$log->question_id = $question->id;
        $log->save();

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

		$log = new Log();
        $log->performed_task = 'Foi removida a Questão: '.$question->question.' que se encontrava na posição '.$orderOfQuestion.'.';
		$log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
        $log->save();

		$log = new Log();
        $log->performed_task = 'Foi removida do Questionário: '.$quiz->name.'.';
		$log->done_by = Auth::user()->id;
		$log->question_id = $question->id;
        $log->save();

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	public function upQuestion(Quiz $quiz, Question $question)
	{
		$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;
		$aboveQuestion = $quiz->questions()->where('order', $orderOfQuestion - 1)->first();
		$quiz->questions()->updateExistingPivot($question->id, ['order' => $orderOfQuestion - 1]);
		$quiz->questions()->updateExistingPivot($aboveQuestion->id, ['order' => $orderOfQuestion]);

		$log = new Log();
        $log->performed_task = 'A Questão: '.$question->question.' foi movida para um lugar acima na lista de questões.';
        $log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
        $log->save();

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	public function downQuestion(Quiz $quiz, Question $question)
	{
		$orderOfQuestion = DB::table('quiz_question')->select('order')->where([['quiz_id', $quiz->id], ['question_id', $question->id]])->first()->order;
		$aboveQuestion = $quiz->questions()->where('order', $orderOfQuestion + 1)->first();
		$quiz->questions()->updateExistingPivot($question->id, ['order' => $orderOfQuestion + 1]);
		$quiz->questions()->updateExistingPivot($aboveQuestion->id, ['order' => $orderOfQuestion]);

		$log = new Log();
        $log->performed_task = 'A Questão: '.$question->question.' foi movida para um lugar abaixo na lista de questões.';
        $log->done_by = Auth::user()->id;
		$log->quiz_id = $quiz->id;
        $log->save();

        return redirect()->route('quizs.questions', ['quiz' => $quiz->id]); 
	}

	private function saveDataFieldsToSession(Request $request)
    {
        $request->session()->put('quizName', $request->input('quizName'));
        $request->session()->put('quizCreator', $request->input('quizCreator'));
		$request->session()->put('quizSort', $request->input('quizSort'));
        $request->session()->put('quizPages', $request->input('quizPages'));
		$request->session()->put('quizBlocked', $request->input('quizBlocked'));
    }

    private function retrieveDataFieldsFromSessionToArray(Request $request, $searchData)
    {
        $searchData['quizName'] = $request->session()->get('quizName');
        $searchData['quizCreator'] = $request->session()->get('quizCreator');
        $searchData['quizSort'] = $request->session()->get('quizSort');
        $searchData['quizPages'] = $request->session()->get('quizPages');
		$searchData['quizBlocked'] = $request->session()->get('quizBlocked');
        return $searchData;
    }

    private function isRequestDataEmpty(Request $request)
    {
        if(!$request->has('quizName') && !$request->has('quizCreator') 
			&& !$request->has('quizSort') && !$request->has('quizPages') 
			&& !$request->has('quizBlocked')) {
            return true;
        }
        return false;
    }
}
