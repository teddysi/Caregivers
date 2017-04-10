<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Material
{
    protected static $singleTableType = 'image';

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