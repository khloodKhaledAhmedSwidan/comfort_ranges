<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComplaintsTable extends Migration {

	public function up()
	{
		Schema::create('complaints', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->text('description');
			$table->integer('user_id')->unsigned();
			$table->integer('order_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('complaints');
	}
}