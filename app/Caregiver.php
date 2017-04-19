<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends User
{
    protected static $singleTableType = 'caregiver';

    protected static $persisted = ['rate', 'location', 'caregiver_token', 'login_count', 'created_by'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate', 'location', 'caregiver_token', 'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo('App\HealthcarePro', 'created_by', 'id');
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

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

}
