<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillsTable extends Migration {

	public function up()
	{
		Schema::create('bills', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255)->nullable();
			$table->text('description')->nullable();
			$table->decimal('price', 10,2)->nullable();
			$table->integer('order_id')->unsigned();
			$table->integer('is_pay')->default('0');
			$table->enum('status', array('1', '2', '3', '4'))->nullable();
		});
	}

	public function down()
	{
		Schema::drop('bills');
	}
}