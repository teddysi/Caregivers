<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    protected static $singleTableType = 'admin';
}
