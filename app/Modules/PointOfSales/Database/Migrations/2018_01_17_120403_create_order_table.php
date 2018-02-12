<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderTable extends Migration {

	public function up()
	{
		Schema::create('order', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('order_number', 255);
			$table->string('real_invoice',255);
			$table->integer('customer_id')->unsigned();
			$table->integer('owner')->unsigned();
			$table->integer('website')->unsigned();
			$table->integer('unique_code')->nullable();
			$table->string('status');
			$table->float('total', 15,2)->default('0');
			$table->integer('inquiry_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('order');
	}
}