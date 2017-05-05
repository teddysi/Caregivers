<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annex extends Material
{
    protected static $singleTableType = 'annex';

    protected static $persisted = ['url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
    ];
}
