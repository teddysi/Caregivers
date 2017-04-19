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

    protected static $persisted = ['username', 'name', 'email', 'password'];

    protected static $singleTableSubclasses = [Admin::class, HealthcarePro::class, 
        Caregiver::class];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function logs()
    {
        return $this->hasMany('App\Log', 'user_id', 'id');
    }
    
}




