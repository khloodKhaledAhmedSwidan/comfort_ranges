<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->text('description')->nullable();
			$table->string('title_ar', 255)->nullable();
			$table->text('description_ar')->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('order_id')->unsigned()->nullable();
			$table->integer('is_read')->default('0')->nullable();
			$table->integer('bill_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}
