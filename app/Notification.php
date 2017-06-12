<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'text', 'viewed',
    ];

    public function creator()
    {
        return $this->belongsTo('App\Caregiver', 'created_by', 'id');
    }
}
