<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->string('type');
            $table->string('model')->nullable();
            $table->string('path')->nullable();
            $table->string('mime')->nullable();
            $table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('caregiver_id')->nullable()->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
            $table->integer('patient_id')->nullable()->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients');
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
        Schema::dropIfExists('evaluations');
    }
}
