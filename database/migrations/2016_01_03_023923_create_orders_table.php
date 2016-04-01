<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('order_number', 10);
            $table->string('address_line1');
            $table->string('address_line2');
            $table->string('address_city');
            $table->string('address_state');
            $table->string('address_zip');
            $table->string('address_country');
            $table->boolean('paid')->default(0);
            $table->string('gateway')->nullable();
            $table->integer('amount')->unsigned()->nullable();
            $table->string('charge_id')->nullable();
            $table->integer('shipping_amount')->unsigned()->nullable();
            $table->string('shipping_location')->nullable();
            $table->boolean('fulfilled')->default(0);
            $table->boolean('cancelled')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
