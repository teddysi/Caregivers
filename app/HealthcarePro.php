<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthcarePro extends User
{
    protected static $singleTableType = 'healthcarepro';

    protected static $persisted = ['facility', 'job'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility', 'job'
    ];

    public function caregivers()
    {
        return $this->belongsToMany('App\Caregiver');
    }

    public function patients()
    {
        return $this->hasMany('App\Patient', 'created_by', 'id');
    }

    public function needs()
    {
        return $this->hasMany('App\Need', 'created_by', 'id');
    }

    public function materials()
    {
        return $this->hasMany('App\Material', 'created_by', 'id');
    }
}
