<?php

namespace Thanatos\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model {
	use SoftDeletes;

	protected $table = 'product_category';
	public $timestamps = true;
	protected $fillable = array('name', 'description', 'code','parent_id');
	protected $visible = array('id','name', 'description', 'code','parent_id');
	protected $dates = ['deleted_at'];
	
	public function parent()
	 {
			 return $this->belongsTo('Thanatos\Modules\Inventory\Models\ProductCategory', 'parent_id');
	 }
	 public function products()
	 {
		 return $this->belongsToMany('Thanatos\Modules\Inventory\Models\Products');
	 }
}
