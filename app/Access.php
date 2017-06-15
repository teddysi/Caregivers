<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = [
        'caregiver_id', 'material_id', 'patient_id',
    ];

    public function material()
    {
        return $this->belongsTo('App\Material', 'material_id', 'id');
    }

    public function caregiver()
    {
        return $this->belongsTo('App\Caregiver');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
