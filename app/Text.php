<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Text extends Material
{
    protected static $singleTableType = 'text';

    protected static $persisted = ['body'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
    ];
}