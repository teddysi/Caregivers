<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends User
{
    protected static $singleTableType = 'caregiver';

    protected static $persisted = ['rate', 'caregiver_token'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate', 'caregiver_token'
    ];

    public function healthcarePros()
    {
        return $this->belongsToMany('App\HealthcarePro');
    }

    public function patients()
    {
        return $this->hasMany('App\Patient');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

}
