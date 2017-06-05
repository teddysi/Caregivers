<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answer', 
    ];

    public function answeredBy()
    {
        return $this->belongsTo('App\User', 'answered_by', 'id');
    }

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id', 'id');
    }

    public function evaluation()
    {
        return $this->belongsTo('App\Evaluation', 'evaluation_id', 'id');
    }
}
