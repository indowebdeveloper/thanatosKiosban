<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItems extends Model 
{

    protected $table = 'order_items';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    public function order()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Order', 'order_id');
    }

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

    public function assignedUser()
    {
        return $this->belongsTo('Thanatos\PureUser', 'assignment');
    }
}