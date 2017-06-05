<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizCaregiverPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_caregiver', function (Blueprint $table) {
            $table->integer('quiz_id')->unsigned()->index();
            $table->foreign('quiz_id')->references('id')->on('quizs');
            $table->integer('caregiver_id')->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
            $table->integer('evaluation_id')->unsigned()->index();
            $table->primary(['quiz_id', 'caregiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_caregiver');
    }
}
