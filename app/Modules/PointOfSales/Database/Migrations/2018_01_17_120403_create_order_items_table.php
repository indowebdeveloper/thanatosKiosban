<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemsTable extends Migration {

	public function up()
	{
		Schema::create('order_items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('order_id')->unsigned();
			$table->integer('product_id');
			$table->string('name');
			$table->float('price', 10,2);
			$table->float('capital_price', 10,2);
			$table->double('qty');
			$table->double('bought')->nullable()->default('0');
			$table->longText('image');
			$table->string('product_code');
			$table->float('subtotal', 10,2)->default('0');
			$table->float('shipping_cost', 10,2)->nullable()->default('0');
			$table->string('shipping_expedition')->nullable();
			$table->longText('shipping_address')->nullable();
			$table->string('sender_name')->nullable();
			$table->longText('greetings')->nullable();
			$table->string('receiver_name')->nullable();
			$table->string('receiver_phone')->nullable();
			$table->longText('notes')->nullable();
			$table->integer('city')->unsigned();
			$table->integer('country')->unsigned();
			$table->integer('province')->unsigned();
			$table->datetime('date_time');
		});
	}

	public function down()
	{
		Schema::drop('order_items');
	}
}