<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    protected static $singleTableType = 'admin';

    protected static $persisted = ['blocked'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blocked'
    ];
}
