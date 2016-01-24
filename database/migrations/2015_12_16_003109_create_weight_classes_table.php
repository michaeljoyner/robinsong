<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_location_id')->unsigned();
            $table->foreign('shipping_location_id')->references('id')->on('shipping_locations')->onDelete('cascade');
            $table->integer('weight_limit');
            $table->integer('price');
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
        Schema::drop('weight_classes');
    }
}
