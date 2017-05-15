<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    
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

    protected $fillable = [
        'name', 'description', 'path', 'mime',
    ];

}
