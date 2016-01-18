<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            // each project can have more than one group of conditions
            $table->integer('condition_group')->unsigned();
            // type such as IF
            $table->string('type');
            // Equal to, more than, of type
            $table->string('operator');
            // IF $CI_BUILD passed, Type is Issue, etc...
            $table->string('value');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('condition');
    }
}
