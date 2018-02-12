<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductCategoryTable extends Migration {

	public function up()
	{
		Schema::create('product_category', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->text('description');
			$table->string('code');
		});
	}

	public function down()
	{
		Schema::drop('product_category');
	}
}