<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompositeMaterialPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composite_material', function (Blueprint $table) {
            $table->integer('composite_id')->unsigned()->index();
            $table->foreign('composite_id')->references('id')->on('materials');
            $table->integer('material_id')->unsigned()->index();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->integer('order')->unsigned()->index();
            $table->primary(['composite_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('composite_material');
    }
}
