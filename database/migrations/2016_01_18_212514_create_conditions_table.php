<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('condition_id')->unsigned()->nullable();
            // grouping conditions by a name if needed.
            $table->string('group_name');
            // type such as IF
            $table->string('type');
            // Equal to, more than, of type
            $table->string('operator');
            // IF $CI_BUILD passed, Type is Issue, etc...
            $table->string('value');


            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('condition_id')->references('id')->on('conditions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conditions');
    }
}
