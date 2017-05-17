<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    protected $fillable = [
        'description',
    ];

    public function creator()
    {
        return $this->belongsTo('App\HealthcarePro', 'created_by', 'id');
    }

    public function materials()
    {
        return $this->belongsToMany('App\Material');
    }

    public function patients()
    {
        return $this->belongsToMany('App\Patient');
    }

    public function logs()
    {
        return $this->hasMany('App\Need', 'need_id', 'id');
    }
}
