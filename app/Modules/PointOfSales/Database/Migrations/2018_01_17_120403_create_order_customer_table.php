<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderCustomerTable extends Migration {

	public function up()
	{
		Schema::create('order_customer', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('order_id')->unsigned();
			$table->integer('type');
			$table->string('email');
			$table->string('phone');
			$table->string('name');
			$table->integer('company_type')->nullable();
			$table->string('company_name')->nullable();
			$table->longText('company_address')->nullable();
			$table->string('company_email')->nullable();
			$table->string('company_phone')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('order_customer');
	}
}