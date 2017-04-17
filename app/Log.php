<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'performed_task', 
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
