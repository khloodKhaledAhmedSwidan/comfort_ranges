<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	public function up()
	{
		Schema::create('locations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255)->nullable();
			$table->decimal('longitude', 10,8);
			$table->decimal('latitude', 10,8);
			$table->integer('user_id')->unsigned();
			$table->integer('main')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('locations');
	}
}
