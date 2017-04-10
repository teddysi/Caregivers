<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Material extends Model
{

    use SingleTableInheritanceTrait;

    protected $table = "materials";

    protected static $singleTableTypeField = 'type';

    protected static $persisted = ['description', 'name'];

    protected static $singleTableSubclasses = [TextFile::class, Video::class, 
        Image::class];

    protected $fillable = [
        'description', 'name',
    ];

    public function needs()
    {
        return $this->hasMany('App\Need');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding');
    }

}
