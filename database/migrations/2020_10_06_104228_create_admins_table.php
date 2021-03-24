<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration {

	public function up()
	{
		Schema::create('admins', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('phone', 15)->nullable();
			$table->string('email', 255)->nullable();
			$table->string('password', 255);
			$table->string('remember_token', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('admins');
	}
}
