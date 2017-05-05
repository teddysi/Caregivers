<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Material
{
    protected static $singleTableType = 'video';

    protected static $persisted = ['url', 'path', 'mime'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'path', 'mime',
    ];
}
