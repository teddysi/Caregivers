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

    public function healthcarePros()
    {
        return $this->hasMany('App\HealthcarePro');
    }

    public function needs()
    {
        return $this->hasMany('App\Need');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }
}
