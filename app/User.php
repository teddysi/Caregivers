<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class User extends Authenticatable
{
    use Notifiable;
    use SingleTableInheritanceTrait;

    protected $table = "users";

    protected static $singleTableTypeField = 'role';

    protected static $persisted = ['username', 'name', 'email', 'password', 'blocked'];

    protected static $singleTableSubclasses = [Admin::class, HealthcarePro::class, 
        Caregiver::class];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function caregivers_created()
    {
        return $this->hasMany('App\Caregiver', 'created_by', 'id');
    }

    public function materials_created()
    {
        return $this->hasMany('App\Material', 'created_by', 'id');
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'user_id', 'id');
    }

    public function evaluations_created()
    {
        return $this->hasMany('App\Evaluation', 'created_by', 'id');
    }

    public function questions_created()
    {
        return $this->hasMany('App\Question', 'created_by', 'id');
    }

    public function answers_created()
    {
        return $this->hasMany('App\Answer', 'answer_by', 'id');
    }
    
}




