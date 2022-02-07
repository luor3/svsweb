<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsSshserversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_sshservers', function (Blueprint $table) {
            $table->id()->unique();
            $table->foreignId('job_id')->constrained('jobs');
            $table->foreignId('sshserver_id')->constrained('sshservers');
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
        Schema::dropIfExists('jobs_sshservers');
    }
}
