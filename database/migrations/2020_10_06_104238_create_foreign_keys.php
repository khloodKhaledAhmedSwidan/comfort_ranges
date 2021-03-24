<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
//		Schema::table('users', function(Blueprint $table) {
//			$table->foreign('category_id')->references('id')->on('categories')
//						->onDelete('cascade')
//						->onUpdate('cascade');
//		});
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('branch_id')->references('id')->on('branches')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('rates', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('employee_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('devices', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reports', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('complaints', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('complaints', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('images', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->foreign('bill_id')->references('id')->on('bills')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('locations', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shifts', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
//		Schema::table('users', function(Blueprint $table) {
//			$table->dropForeign('users_category_id_foreign');
//		});
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_branch_id_foreign');
		});
		Schema::table('rates', function(Blueprint $table) {
			$table->dropForeign('rates_user_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_category_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_user_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_employee_id_foreign');
		});
		Schema::table('devices', function(Blueprint $table) {
			$table->dropForeign('devices_user_id_foreign');
		});
		Schema::table('reports', function(Blueprint $table) {
			$table->dropForeign('reports_user_id_foreign');
		});
		Schema::table('complaints', function(Blueprint $table) {
			$table->dropForeign('complaints_user_id_foreign');
		});
		Schema::table('complaints', function(Blueprint $table) {
			$table->dropForeign('complaints_order_id_foreign');
		});
		Schema::table('images', function(Blueprint $table) {
			$table->dropForeign('images_order_id_foreign');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropForeign('notifications_user_id_foreign');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropForeign('notifications_order_id_foreign');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropForeign('notifications_bill_id_foreign');
		});
		Schema::table('locations', function(Blueprint $table) {
			$table->dropForeign('locations_user_id_foreign');
		});
		Schema::table('shifts', function(Blueprint $table) {
			$table->dropForeign('shifts_user_id_foreign');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->dropForeign('bills_order_id_foreign');
		});
	}
}
