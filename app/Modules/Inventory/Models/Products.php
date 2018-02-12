<?php

namespace Thanatos\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Products extends Model 
{

    protected $table = 'products';
    public $timestamps = true;
    use Eloquence;
    use SoftDeletes;
    protected $searchableColumns = ['name', 'description','product_code'];
    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'price', 'capital_price', 'sale_price', 'qty', 'image', 'country', 'province', 'city', 'product_code','category_id','description');
    protected $visible = array('id','name', 'price', 'capital_price', 'sale_price', 'qty', 'image', 'country', 'province', 'city', 'product_code','category_id','description');

    public function category()
    {
        return $this->hasOne('Thanatos\Modules\Inventory\Models\ProductCategory');
    }

}