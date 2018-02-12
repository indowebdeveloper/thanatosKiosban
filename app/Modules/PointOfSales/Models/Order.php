<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model 
{

    protected $table = 'order';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public static $snakeAttributes = false;
    //protected $fillable = array('order_number', 'status', 'total');
    //protected $visible = array('status', 'total');
    protected $guarded = ['id'];
    public function cart()
    {
        return $this->hasMany('Thanatos\Modules\PointOfSales\Models\OrderItems', 'order_id');
    }

    public function customerData()
    {
        return $this->hasOne('Thanatos\Modules\PointOfSales\Models\OrderCustomer');
    }

    public function websiteData()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Website', 'website');
    }

    public function inquiry()
    {
        return $this->belongsTo('Thanatos\Modules\PointOfSales\Models\Enquiries', 'inquiry_id');
    }

    public function customer()
    {
        return $this->belongsTo('Thanatos\Modules\Customer\Models\Customers', 'customer_id');
    }

    public function owner()
    {
        return $this->belongsTo('Thanatos\PureUser', 'owner');
    }

    public function payments()
    {
        return $this->hasMany('Thanatos\Modules\PointOfSales\Models\OrderPayments', 'order_id');
    }

    /**
     * @param array $invoice_items
     */
    public function syncOrderItems(array $invoice_items)
    {
        $children = $this->cart;
        $invoice_items = collect($invoice_items);
        $deleted_ids = $children->filter(
            function ($child) use ($invoice_items) {
                return empty(
                    $invoice_items->where('id', $child->id)->first()
                );
            }
        )->map(function ($child) {
                $id = $child->id;
                $child->delete();
                return $id;
            }
        );
        $attachments = $invoice_items->filter(
            function ($invoice_item) {
                return empty($invoice_item['id']);
            }
        )->map(function ($invoice_item) use ($deleted_ids) {
                $invoice_item['id'] = $deleted_ids->pop();
                return new OrderItems($invoice_item);
        });
        $this->cart()->saveMany($attachments);
    }
}