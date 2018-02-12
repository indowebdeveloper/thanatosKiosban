<?php

namespace Thanatos\Modules\Purchasing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model 
{

    protected $table = 'supplier';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    

    public function geoCity()
    {
        return $this->belongsTo('Igaster\LaravelCities\Geo', 'city');
    }

    public function geoProvince()
    {
        return $this->belongsTo('Igaster\LaravelCities\Geo', 'province');
    }

    public function geoCountry()
    {
        return $this->belongsTo('Igaster\LaravelCities\Geo', 'country');
    }

}