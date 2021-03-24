<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{

    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('phone', 15)->nullable();
            $table->string('whatsapp', 15)->nullable();
            $table->text('term')->nullable();
            $table->text('condition')->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->integer('tax_for_completed_order')->default(0);
            $table->integer('cancel_order')->default(1);

            $table->text('terms_ar')->nullable();
            $table->text('condition_ar')->nullable();
            $table->text('about_us')->nullable();
            $table->text('about_us_ar')->nullable();
            $table->integer('search_range')->nullable();
            $table->integer('shift_range')->nullable();
            $table->integer('count_of_order_in_period')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('route_name', 255)->nullable();
            $table->string('city_name', 255)->nullable();
            $table->string('accept_tax', 255)->nullable();
            $table->string('accept_tax_en', 255)->nullable();
            $table->string('country_name', 255)->nullable();
            $table->decimal('latitude', 10,8)->nullable();
            $table->decimal('longitude', 10,8)->nullable();
            $table->string('company_name_en', 255)->nullable();
            $table->string('route_name_en', 255)->nullable();
            $table->string('city_name_en', 255)->nullable();
            $table->string('country_name_en', 255)->nullable();
            $table->integer('tax_for_completed_order_active')->default(0);

        });
    }

    public function down()
    {
        Schema::drop('settings');
    }
}
