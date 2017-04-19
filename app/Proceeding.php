<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proceeding extends Model
{
    protected $fillable = [
        'note', 'caregiver_id', 'material_id', 'need_id', 'patient_id',
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
