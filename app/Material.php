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

    protected static $singleTableSubclasses = [EmergencyContact::class, Text::class, Video::class, 
        Image::class, Annex::class, Composite::class];

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
        return $this->hasMany('App\Access', 'material_id', 'id');
    }

    public function materials()
    {
        return $this->belongsToMany('App\Material', 'composite_material', 'composite_id', 'material_id');
    }

    public function composites()
    {
        return $this->belongsToMany('App\Material', 'composite_material', 'material_id', 'composite_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Log', 'material_id', 'id');
    }

    public function quizs($id)
    {
        return $this->belongsToMany('App\Quiz', 'quiz_material', 'material_id', 'quiz_id')->where('caregiver_id', $id);
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation', 'material_id', 'id');
    }
}
