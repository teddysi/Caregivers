<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer', 
    ];

    public function answeredBy()
    {
        return $this->belongsTo('App\User', 'answered_by', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id', 'id');
    }

    public function evaluation()
    {
        return $this->belongsTo('App\Evaluation', 'evaluation_id', 'id');
    }
}
