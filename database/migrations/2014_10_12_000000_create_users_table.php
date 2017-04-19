<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('rate')->nullable();
            $table->string('location')->nullable();
            $table->string('facility')->nullable();
            $table->string('job')->nullable();
            $table->integer('login_count')->default(0);
            $table->string('caregiver_token')->nullable();
            $table->boolean('blocked')->default(false);
            $table->integer('created_by')->nullable()->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
