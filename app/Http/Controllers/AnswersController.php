<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caregiver;
use App\Quiz;
use App\Answer;
use App\Evaluation;
use App\Notification;
use DB;

class AnswersController extends Controller
{
    public function submitQuizs(Request $request, $id)
    {
        $caregiver_token = $request->header('Authorization');
        $user = Caregiver::find($id);

        if ($user == null) {
           return response('Não Encontrado', 404);
        }

        /*if (!$caregiver_token || $user->caregiver_token != $caregiver_token) {
            return response('Não Autorizado', 401);
        }*/

        $quizs = $request->input();
        foreach ($quizs as $quiz) {
            $quizObj = Quiz::find($quiz["id"]);
            $evaluation_id;
            if ($quiz["reference"] == "caregiver") {
                $evaluation_id = DB::table('quiz_caregiver')->select('evaluation_id')->where([['quiz_id', $quiz["id"]], ['caregiver_id', $quiz["reference_id"]]])->first()->evaluation_id;
                $quizObj->caregivers()->detach($quiz["reference_id"]);
            } else if ($quiz["reference"] == "patient") {
                $evaluation_id = DB::table('quiz_patient')->select('evaluation_id')->where([['quiz_id', $quiz["id"]], ['patient_id', $quiz["reference_id"]]])->first()->evaluation_id;
                $quizObj->patients()->detach($quiz["reference_id"]);
            } else if ($quiz["reference"] == "material") {
                $evaluation_id = DB::table('quiz_material')->select('evaluation_id')->where([['quiz_id', $quiz["id"]], ['material_id', $quiz["reference_id"]]])->first()->evaluation_id;
                $quizObj->materials($id)->detach($quiz["reference_id"]);
            }

            $evaluation = Evaluation::find($evaluation_id);
            $answer;
            foreach ($quiz["questions"] as $question) {
                $answer = new Answer();
                $answer->answered_by = $id;
                $answer->question_id = $question["id"];
                $answer->quiz_id = $quiz["id"];
                if ($question["type"] != "text") {
                    $possible_answers = explode(";", $question["values"]);
                    $answer->answer = $possible_answers[$question["response"]];
                } else {
                    $answer->answer = $question["response"];
                }
                $answer->evaluation_id = $evaluation_id;
                
                $answer->save();
            }

            $evaluation->answered_at = $answer->created_at;
            $evaluation->save();

            $notification = new Notification();
            $notification->text = 'O Cuidador '.$user->username.' respondeu à Avaliação '.$evaluation->description.'.';
            $notification->created_by = $id;
            $notification->type = 'evaluation';
            $notification->evaluation_id = $evaluation->id;
            $notification->save();
        }

        return response()->json("Quizs Submitted successfully");
    }
}
