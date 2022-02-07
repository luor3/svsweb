<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemotejobsTable extends Migration
{
    /**
     * Run the migrations.
     * -  the job state:
     * E -  Job is exiting after having run.
     * H -  Job is held.
     * Q -  job is queued, eligable to run or routed.
     * R -  job is running.
     * T -  job is being moved to new location.
     * W -  job is waiting for its execution time
     *   (-a option) to be reached.
     * S -  (Unicos only) job is suspend.
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
