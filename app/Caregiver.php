<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends User
{
    protected static $singleTableType = 'caregiver';

    protected static $persisted = ['location', 'caregiver_token', 'login_count', 'created_by'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location', 'caregiver_token', 'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function healthcarePros()
    {
        return $this->belongsToMany('App\HealthcarePro');
    }

    public function patients()
    {
        return $this->hasMany('App\Patient');
    }

    public function materials()
    {
        return $this->belongsToMany('App\Material');
    }

    public function accesses()
    {
        return $this->hasMany('App\Access');
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }

    public function answeredBy()
    {
        return $this->hasMany('App\Answer', 'answered_by', 'id');
    }

    public function quizs()
    {
        return $this->belongsToMany('App\Quiz', 'quiz_caregiver', 'caregiver_id', 'quiz_id');
    }

    public function notificationsCreated()
    {
        return $this->hasMany('App\Notification', 'created_by', 'id');
    }
}
