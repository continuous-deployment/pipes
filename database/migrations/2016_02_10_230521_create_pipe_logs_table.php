<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePipeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipe_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('severity');
            $table->string('message')->nullable();
            $table->text('output')->nullable();
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
        Schema::drop('pipe_logs');
    }
}
