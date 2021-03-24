<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicesTable extends Migration {

	public function up()
	{
		Schema::create('devices', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('device_token', 255);
			$table->integer('user_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('devices');
	}
}