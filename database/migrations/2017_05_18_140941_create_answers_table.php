<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('answer');
            $table->integer('answered_by')->unsigned()->index();
            $table->foreign('answered_by')->references('id')->on('users');
            $table->integer('question_id')->unsigned()->index();
            $table->foreign('question_id')->references('id')->on('questions');
            $table->integer('quiz_id')->unsigned()->index();
            $table->foreign('quiz_id')->references('id')->on('quizs');
            $table->integer('evaluation_id')->unsigned()->dafault(null)->index();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
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
        Schema::dropIfExists('answers');
    }
}