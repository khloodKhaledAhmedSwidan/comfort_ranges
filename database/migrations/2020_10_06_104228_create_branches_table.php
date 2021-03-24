<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchesTable extends Migration {

	public function up()
	{
		Schema::create('branches', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255)->nullable();
			$table->decimal('latitude', 10,8)->nullable();
			$table->decimal('longitude', 10,8)->nullable();
			$table->string('name_ar', 255);
		});
	}

	public function down()
	{
		Schema::drop('branches');
	}
}