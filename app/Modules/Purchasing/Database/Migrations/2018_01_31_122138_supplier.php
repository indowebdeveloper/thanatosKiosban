<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Supplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->longtext('operational_hour')->nullable();
            $table->longText('speciality')->nullable();
            $table->longText('address')->nullable();
            $table->integer('country')->nullable()->unsigned();
			$table->integer('province')->nullable()->unsigned();
			$table->integer('city')->nullable()->unsigned();
            $table->string('contact_person', 255)->nullable();
            $table->string('account_number', 255)->nullable();
            $table->string('account_holder', 255)->nullable();
            $table->string('bank', 255)->nullable();
            $table->string('payment_terms', 255)->nullable();
            $table->string('supplier_code', 255)->nullable();
            $table->double('longitude');
            $table->double('latitude');
            $table->double('this_week_orders')->default(0);
            $table->softDeletesTz();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('supplier');
    }
}
