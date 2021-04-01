<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('category_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->decimal('price', 10,2)->nullable();
			$table->decimal('total', 10,2)->nullable();
			$table->decimal('material_cost', 10,2)->nullable();
			$table->time('from')->nullable();
			$table->time('work_duration')->nullable();
			$table->time('to')->nullable();
			$table->integer('employee_id')->unsigned()->nullable();
			$table->text('note')->nullable();
			$table->string('notes_on_what_was_done',225)->nullable();
			$table->text('employee_note')->nullable();
			$table->integer('tax')->nullable();
			$table->integer('is_paid')->nullable();
			$table->integer('status');
			$table->integer('complete_in_another_day')->default(0)->nullable();
            $table->decimal('longitude', 10,8)->nullable();
            $table->decimal('latitude', 10,8)->nullable();
			$table->integer('order_shift_id')->unsigned();
			$table->integer('number_order_in_time')->nullable();
            $table->foreign('order_shift_id')->references('id')->on('order_shifts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('completed_order_accepte_tax')->nullable();
            $table->integer('location_id')->nullable();
			$table->integer('real_num')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('vedio', 255)->nullable();

            $table->date('date')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}
