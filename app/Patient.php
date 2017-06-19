<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name', 'email', 'location', 
    ];

    public function caregiver()
    {
        return $this->belongsTo('App\Caregiver');
    }

    public function creator()
    {
        return $this->belongsTo('App\HealthcarePro', 'created_by', 'id');
    }

    public function needs()
    {
        return $this->belongsToMany('App\Need');
    }

    public function accesses()
    {
        return $this->hasMany('App\Access');
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'patient_id', 'id');
    }

    public function quizs()
    {
        return $this->belongsToMany('App\Quiz', 'quiz_patient', 'patient_id', 'quiz_id');
    }
}
