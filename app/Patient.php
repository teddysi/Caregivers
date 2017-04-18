<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name', 'email', 
    ];

    public function caregiver()
    {
        return $this->belongsTo('App\Caregiver');
    }

    public function healthcare_pro()
    {
        return $this->belongsTo('App\HealthcarePro');
    }

    public function needs()
    {
        return $this->belongsToMany('App\Need');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }
}
