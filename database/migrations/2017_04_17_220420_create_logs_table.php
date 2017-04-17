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
            $table->integer('admin_id')->nullable()->unsigned()->index();
            $table->foreign('admin_id')->references('id')->on('users');
            $table->integer('healthcare_pro_id')->nullable()->unsigned()->index();
            $table->foreign('healthcare_pro_id')->references('id')->on('users');
            $table->integer('caregiver_id')->nullable()->unsigned()->index();
            $table->foreign('caregiver_id')->references('id')->on('users');
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
