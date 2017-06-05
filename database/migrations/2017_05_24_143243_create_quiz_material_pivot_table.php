<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizMaterialPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_material', function (Blueprint $table) {
            $table->integer('quiz_id')->unsigned()->index();
            $table->foreign('quiz_id')->references('id')->on('quizs');
            $table->integer('material_id')->unsigned()->index();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->integer('caregiver_id')->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
            $table->integer('evaluation_id')->unsigned()->index();
            $table->primary(['quiz_id', 'material_id', 'caregiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_material');
    }
}
