<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('phone', 12);
            $table->string('name', 255);
//			$table->integer('category_id')->unsigned()->nullable();
			$table->integer('active')->default('0');
			$table->enum('type', array('1', '2'));
			$table->string('image	',255)->default('default.png');
			$table->string('password', 255);
			$table->string('api_token', 255)->nullable();
			$table->string('verification_code', 255)->nullable();
			$table->integer('branch_id')->unsigned()->nullable();
			$table->integer('available_orders')->default(0);
			$table->enum('language', array('en', 'ar'))->default('ar');
            $table->rememberToken();

        });
	}

	public function down()
	{
		Schema::drop('users');
	}
}
