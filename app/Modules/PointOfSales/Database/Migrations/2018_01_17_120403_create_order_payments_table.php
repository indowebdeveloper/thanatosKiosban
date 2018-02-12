<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderPaymentsTable extends Migration {

	public function up()
	{
		Schema::create('order_payments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->datetime('transaction_date');
			$table->integer('order_id')->unsigned();
			$table->string('transaction_id')->nullable();
			$table->string('payment_method');
			$table->float('amount', 15,2)->default('0');
			$table->longText('notes')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('order_payments');
	}
}