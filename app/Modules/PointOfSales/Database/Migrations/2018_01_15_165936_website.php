<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->longText('slogan')->nullable();
            $table->longText('address')->nullable();
            $table->longText('mail_inboxes')->nullable();
            $table->longText('sales_inboxes')->nullable();
            $table->longText('finance_inboxes')->nullable();
            $table->longText('purchasing_inboxes')->nullable();
            $table->longText('logo')->nullable();
            
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
        Schema::drop('website');
    }
}
