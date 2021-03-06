<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        return $this->hasMany('App\Log', 'need_id', 'id');
    }
}
