<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answer', 
    ];

    public function answer_by()
    {
        return $this->belongsTo('App\User', 'answer_by', 'id');
    }

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id', 'id');
    }


}
