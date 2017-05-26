<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizPatientPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_patient', function (Blueprint $table) {
            $table->integer('quiz_id')->unsigned()->index();
            $table->foreign('quiz_id')->references('id')->on('quizs');
            $table->integer('patient_id')->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->primary(['quiz_id', 'patient_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_patient');    }
}
