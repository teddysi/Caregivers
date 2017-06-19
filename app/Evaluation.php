<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'model', 'description', 'path', 'mime',
    ];

	public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function caregiver()
    {
        return $this->belongsTo('App\Caregiver');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer', 'evaluation_id', 'id');
    }

    public function inquired()
    {
        return $this->belongsTo('App\User', 'answered_by', 'id');
    }

    public function submitter()
    {
        return $this->belongsTo('App\User', 'submitter_by', 'id');
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'evaluation_id', 'id');
    }
}
