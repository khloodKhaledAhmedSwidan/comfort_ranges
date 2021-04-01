<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRejectedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_shift_id')->unsigned();
            $table->foreign('order_shift_id')->references('id')->on('order_shifts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('rejected_date_id')->unsigned();
            $table->foreign('rejected_date_id')->references('id')->on('rejected_dates')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('rejected_users');
    }
}
