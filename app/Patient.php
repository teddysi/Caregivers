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

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }
}
