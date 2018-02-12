<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomer extends Model 
{

    protected $table = 'order_customer';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    public function order()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Order');
    }

}