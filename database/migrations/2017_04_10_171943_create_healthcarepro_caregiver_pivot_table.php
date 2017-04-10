<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthcareproCaregiverPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healthcarepro_caregiver', function (Blueprint $table) {
            $table->integer('healthcarepro_id')->unsigned()->index();
            $table->foreign('healthcarepro_id')->references('id')->on('users');
            $table->integer('caregiver_id')->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
            $table->primary(['healthcarepro_id', 'caregiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('healthcarepro_caregiver');
    }
}
