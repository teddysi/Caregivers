<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Material extends Model
{

    use SingleTableInheritanceTrait;

    protected $table = "materials";

    protected static $singleTableTypeField = 'type';

    protected static $persisted = ['description', 'name', 'created_by', 'blocked'];

    protected static $singleTableSubclasses = [EmergencyContact::class,TextFile::class, Video::class, 
        Image::class];

    protected $fillable = [
        'description', 'name', 'created_by', 'blocked',
    ];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function caregivers()
    {
        return $this->belongsToMany('App\Caregiver');
    }

    public function needs()
    {
        return $this->belongsToMany('App\Need');
    }

    public function proceedings()
    {
        return $this->hasMany('App\Proceeding', 'material_id', 'id');
    }

}
