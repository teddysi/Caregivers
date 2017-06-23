<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Composite extends Material
{
    protected static $singleTableType = 'composite';

    public function materials()
    {
        return $this->belongsToMany('App\Material', 'composite_material', 'composite_id', 'material_id');
    }
}
