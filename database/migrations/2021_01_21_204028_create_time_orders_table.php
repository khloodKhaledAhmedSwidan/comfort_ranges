<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->date('date');
            $table->integer('order_shift_id')->unsigned();

            $table->foreign('order_shift_id')->references('id')->on('order_shifts')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('time_orders');
    }
}
