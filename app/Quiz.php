<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'blocked',
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
        return $this->belongsTo('App\HealthcarePro', 'created_by', 'id');
    }

    public function caregivers()
    {
        return $this->belongsToMany('App\Caregiver', 'quiz_caregiver', 'quiz_id', 'caregiver_id');
    }

    public function patients()
    {
        return $this->belongsToMany('App\Patient', 'quiz_patient', 'quiz_id', 'patient_id');
    }

    public function materials($id)
    {
        return $this->belongsToMany('App\Material', 'quiz_material', 'quiz_id', 'material_id')->where('caregiver_id', $id);
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'quiz_id', 'id');
    }
}
