<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
	        $table->increments('id');
            $table->text('performed_task');
            $table->integer('done_by')->unsigned()->index();
            $table->foreign('done_by')->references('id')->on('users');
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('patient_id')->nullable()->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->integer('need_id')->nullable()->unsigned()->index();
            $table->foreign('need_id')->references('id')->on('needs');
            $table->integer('material_id')->nullable()->unsigned()->index();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->integer('evaluation_id')->nullable()->unsigned()->index();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
            $table->integer('quiz_id')->nullable()->unsigned()->index();
            $table->foreign('quiz_id')->references('id')->on('quizs');
            $table->integer('question_id')->nullable()->unsigned()->index();
            $table->foreign('question_id')->references('id')->on('questions');
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
        Schema::drop('logs');
    }
}
