<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poll_id')->unsigned();
            $table->integer('answer_id')->unsigned();
            $table->ipAddress('ip');
            $table->string('country');
            $table->string('language');
            $table->string('hardware_concurrency');
            $table->string('timezone');
            $table->string('platform');
            $table->timestamps();

            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('answer_id')->references('id')->on('poll_answers');
            $table->index('poll_id');
            $table->index('answer_id');
            $table->index('ip');
            $table->index('country');
            $table->index('language');
            $table->index('hardware_concurrency');
            $table->index('timezone');
            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
