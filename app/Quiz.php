<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizs';

    protected $fillable = [
        'name', 
    ];

    public function answers()
    {
    	return $this->hasMany('App\Answer');
    }

    public function questions()
    {
    	return $this->belongsToMany('App\Question', 'quiz_question', 'quiz_id', 'question_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
