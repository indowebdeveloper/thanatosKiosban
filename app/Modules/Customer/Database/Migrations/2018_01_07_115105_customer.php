<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('company_type', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
            $table->longText('description')->nullable(); 
		});
        Schema::create('customer', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->integer('type'); // 0 = personal, 1 = company
            $table->integer('company_type')->nullable()->unsigned();
            $table->string('company_name',255)->nullable();
            $table->longText('company_address')->nullable();
            $table->string('company_email', 255)->nullable();
            $table->string('company_phone', 255)->nullable(); 
            $table->foreign('company_type')->references('id')->on('company_type')->onDelete('cascade');
            $table->float('points',20,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_type');
        Schema::drop('customer');
    }
}
