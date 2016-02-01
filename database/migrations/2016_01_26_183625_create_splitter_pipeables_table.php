<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSplitterPipeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('splitter_pipeables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('splitter_id')->unsigned();
            $table->integer('pipeable_id')->unsigned()->nullable();
            $table->string('pipeable_type')->nullable();
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
        Schema::drop('splitter_pipeables');
    }
}
