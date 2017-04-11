<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Material
{
    protected static $singleTableType = 'emergencyContact';

    protected static $persisted = ['number'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
    ];
}
