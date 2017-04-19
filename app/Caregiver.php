<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends User
{
    protected static $singleTableType = 'caregiver';

    protected static $persisted = ['rate', 'caregiver_token', 'login_count'];

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

    public function materials()
    {
        return $this->belongsToMany('App\Material');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

}
