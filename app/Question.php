<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 
    ];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function answers()
    {
    	return $this->hasMany('App\Answer');
    }

    public function quizs()
    {
        return $this->belongsToMany('App\Quiz', 'quiz_question', 'question_id', 'quiz_id');
    }
}

