<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemotejobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remotejobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->unique();
            $table->string('remote_job_id');
            $table->enum('job_state', ['Q','H','E','R','T','W','S']);
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
        Schema::dropIfExists('remotejobs');
    }
}
