<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateSentencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_sentence', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rate_id')->unsigned();
            $table->foreign('rate_id')->references('id')->on('rates')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sentence_id')->unsigned();
            $table->foreign('sentence_id')->references('id')->on('sentences')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_sentence');
    }
}
