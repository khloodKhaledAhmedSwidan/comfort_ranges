<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('name_ar', 255);
            $table->string('image', 255)->nullable();
            $table->integer('branch_id')->unsigned();
            $table->integer('arranging')->unique();
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('active')->default(0);

        });
	}

	public function down()
	{
		Schema::drop('categories');
	}
}
