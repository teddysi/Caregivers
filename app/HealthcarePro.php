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
    
}
