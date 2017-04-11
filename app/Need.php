<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    protected $fillable = [
        'description',
    ];

    public function materials()
    {
        return $this->belongsToMany('App\Material');
    }

    public function patients()
    {
        return $this->belongsToMany('App\Patient');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

}
