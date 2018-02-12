<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('order', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customer')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order', function(Blueprint $table) {
			$table->foreign('owner')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order', function(Blueprint $table) {
			$table->foreign('website')->references('id')->on('website')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		
		Schema::table('order_customer', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('order')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('order')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('city')->references('id')->on('geo')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('country')->references('id')->on('geo')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('province')->references('id')->on('geo')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('order_payments', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('order')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('order', function(Blueprint $table) {
			$table->dropForeign('order_customer_id_foreign');
		});
		Schema::table('order', function(Blueprint $table) {
			$table->dropForeign('order_owner_foreign');
		});
		Schema::table('order', function(Blueprint $table) {
			$table->dropForeign('order_website_foreign');
		});
		Schema::table('order', function(Blueprint $table) {
			$table->dropForeign('order_inquiry_id_foreign');
		});
		Schema::table('order_customer', function(Blueprint $table) {
			$table->dropForeign('order_customer_order_id_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_order_id_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_city_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_country_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_province_foreign');
		});
		Schema::table('order_payments', function(Blueprint $table) {
			$table->dropForeign('order_payments_order_id_foreign');
		});
	}
}