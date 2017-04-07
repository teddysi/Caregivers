<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proceeding extends Model
{
    protected $fillable = [
        'note', 'caregiver_id', 'material_id', 'need_id', 'patient_id',
    ];
}
