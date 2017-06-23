<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'type', 'values', 'blocked',
    ];

    public function creator()
    {
        return $this->belongsTo('App\HealthcarePro', 'created_by', 'id');
    }

    public function quizs()
    {
        return $this->belongsToMany('App\Quiz', 'quiz_question', 'question_id', 'quiz_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'question_id', 'id');
    }
}

