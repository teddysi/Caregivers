<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaregiverHealthcareProPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caregiver_healthcare_pro', function (Blueprint $table) {
            $table->integer('healthcare_pro_id')->unsigned()->index();
            $table->foreign('healthcare_pro_id')->references('id')->on('users');
            $table->integer('caregiver_id')->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
            $table->primary(['healthcare_pro_id', 'caregiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('caregiver_healthcare_pro');
    }
}
