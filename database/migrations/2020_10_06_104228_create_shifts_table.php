<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftsTable extends Migration {

	public function up()
	{
		Schema::create('shifts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->time('from')->nullable();
			$table->time('to')->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('type')->nullable();
			$table->date('date')->nullable();
			$table->string('title', 255)->nullable();
			$table->integer('status');
		});
	}

	public function down()
	{
		Schema::drop('shifts');
	}
}
