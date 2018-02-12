<?php

namespace Thanatos\Modules\Purchasing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrders extends Model 
{

    protected $table = 'purchase_order';
    public $timestamps = true;
    public static $snakeAttributes = false;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    
    public function orderData()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Order', 'order_id');
    }
    public function prData()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\OrderItems', 'pr_id');
    }
    public function supplierData()
    {
        return $this->belongsTo('Thanatos\Modules\Purchasing\Models\Suppliers', 'supplier_id');
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

}