<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderForeign extends Migration {

	public function up()
	{
		Schema::table('purchase_order', function(Blueprint $table) {
			$table->foreign('supplier_id')->references('id')->on('supplier')
						->onDelete('no action')
						->onUpdate('no action');
			$table->foreign('order_id')->references('id')->on('order')
						->onDelete('no action')
						->onUpdate('no action');			
			$table->foreign('pr_id')->references('id')->on('order_items')
						->onDelete('no action')
						->onUpdate('no action');			
			$table->foreign('country')->references('id')->on('geo')
						->onDelete('no action')
						->onUpdate('no action');
			$table->foreign('province')->references('id')->on('geo')
						->onDelete('no action')
						->onUpdate('no action');
			$table->foreign('city')->references('id')->on('geo')
						->onDelete('no action')
						->onUpdate('no action');			
		});
	}

	public function down()
	{
		Schema::table('purchase_order', function(Blueprint $table) {
			$table->dropForeign('purchase_order_supplier_id_foreign');
			$table->dropForeign('purchase_order_order_id_foreign');
			$table->dropForeign('purchase_order_pr_id_foreign');

			$table->dropForeign('purchase_order_country_foreign');
			$table->dropForeign('purchase_order_province_foreign');
			$table->dropForeign('purchase_order_city_foreign');
		});
	}
}