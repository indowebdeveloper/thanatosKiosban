<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class ProductGeo extends Migration {

	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
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
		Schema::table('products', function(Blueprint $table) {
			$table->dropForeign('products_country_foreign');
			$table->dropForeign('products_province_foreign');
			$table->dropForeign('products_city_foreign');
		});
	}
}