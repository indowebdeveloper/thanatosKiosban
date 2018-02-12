<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 255);
			$table->longText('description')->nullable();
			$table->float('price',10,2);
			$table->float('capital_price',10,2)->nullable()->default(0);
			$table->float('sale_price',10,2)->nullable()->default(0);
			$table->float('qty',10,2)->nullable()->default(0);
			$table->longText('image')->nullable();
			$table->integer('country')->nullable()->unsigned();
			$table->integer('province')->nullable()->unsigned();
			$table->integer('city')->nullable()->unsigned();
			$table->longText('product_code');
            
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}