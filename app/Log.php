<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'performed_task', 
    ];

    public function doneBy()
    {
        return $this->belongsTo('App\User', 'done_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id', 'id');
    }

    public function need()
    {
        return $this->belongsTo('App\Need', 'need_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo('App\Material', 'material_id', 'id');
    }

    public function evaluation()
    {
        return $this->belongsTo('App\Evaluation', 'evaluation_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id', 'id');
    }
}
