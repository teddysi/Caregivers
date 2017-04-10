<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TextFile extends Material
{
    protected static $singleTableType = 'textFile';

    protected static $persisted = ['path'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
    ];
}