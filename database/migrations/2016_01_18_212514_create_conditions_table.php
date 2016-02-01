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
            $table->integer('project_id')->unsigned()->nullable();

            $table->integer('success_pipeable_id')->unsigned()->nullable();
            $table->string('success_pipeable_type')->nullable();

            $table->integer('failure_pipeable_id')->unsigned()->nullable();
            $table->string('failure_pipeable_type')->nullable();

            // grouping conditions by a name if needed.
            $table->string('group_name');
            // type such as IF
            $table->string('type');
            // Field to check value against
            $table->string('field');
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
        Schema::drop('conditions');
    }
}
