<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayments extends Model 
{

    protected $table = 'order_payments';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    public function order()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Order', 'order_id');
    }
    
    public function user()
    {
        return $this->belongsTo('Thanatos\PureUser', 'user_id');
    }
}   