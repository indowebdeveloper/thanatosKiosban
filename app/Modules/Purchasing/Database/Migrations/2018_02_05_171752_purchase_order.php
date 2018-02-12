<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function(Blueprint $table) {
			$table->increments('id');
            $table->timestamps();
            $table->integer('supplier_id')->nullable()->unsigned();
            $table->integer('order_id')->nullable()->unsigned();
            $table->integer('pr_id')->nullable()->unsigned();
            $table->integer('country')->nullable()->unsigned();
			$table->integer('province')->nullable()->unsigned();
            $table->integer('city')->nullable()->unsigned();
            /** Other Fields */
            $table->string('product_name', 255);
            $table->string('product_code', 255);
            $table->longText('image')->nullable();
            $table->integer('qty');
			$table->float('capital_price',10,2);
			$table->float('real_price',10,2);
			$table->float('total',10,2);
            $table->string('sender_name', 255)->nullable();
            $table->string('sender_phone', 255)->nullable();
            $table->string('receiver_name', 255)->nullable();
            $table->string('receiver_phone', 255)->nullable();
            $table->longtext('greetings')->nullable();
            $table->longtext('shipping_address')->nullable();
            $table->datetime('date_time');
            $table->longtext('notes')->nullable();
            $table->longText('real_image')->nullable();
            $table->string('status', 255);
            $table->string('payment_status', 255);
            $table->datetime('shipped_date')->nullable();
            $table->string('tracking_number', 255)->nullable();
            $table->string('shipping_expedition', 255)->nullable();
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
        //
    }
}
