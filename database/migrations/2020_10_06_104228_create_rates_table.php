<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRatesTable extends Migration {

	public function up()
	{
		Schema::create('rates', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('rate');
			$table->integer('user_id')->unsigned();
			$table->string('comment')->nullable();
			$table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');

        });
	}

	public function down()
	{
		Schema::drop('rates');
	}
}
