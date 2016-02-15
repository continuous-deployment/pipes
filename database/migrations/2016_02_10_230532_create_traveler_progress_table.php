<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelerProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travelers_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('bag');
            $table->string('status');
            $table->integer('pipeable_id');
            $table->string('pipeable_type');
            $table->integer('stream_id');
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
        Schema::drop('travelers_progress');
    }
}
