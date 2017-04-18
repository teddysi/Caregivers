<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Material extends Model
{

    use SingleTableInheritanceTrait;

    protected $table = "materials";

    protected static $singleTableTypeField = 'type';

    protected static $persisted = ['description', 'name', 'healthcare_pro_id'];

    protected static $singleTableSubclasses = [EmergencyContact::class,TextFile::class, Video::class, 
        Image::class];

    protected $fillable = [
        'description', 'name', 'healthcare_pro_id',
    ];

    public function healthcare_pro()
    {
        return $this->belongsTo('App\HealthcarePro');
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
        return $this->hasMany('App\Proceeding');
    }

}
