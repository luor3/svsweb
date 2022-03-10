<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use phpseclib\Net\SFTP;
use App\Models\remotejob;
use App\Models\Job;
use Collective\Remote\RemoteFacade as SSH;
use Collective\Remote\Connection;
use \ZipStream\Option\Archive;
use \ZipStream\ZipStream;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\App;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    public const app = "run.pbs";

    public const out = "out.txt";

    public const solver = "EFIEHFMMSERIAL";

    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('inspire')->everyMinute();
        $schedule->call(function () {
            $app = new App();
        $jobs = Job::where('progress', '=', 'pending')->orderBy('created_at', 'desc')->get();

        foreach ($jobs as $j) {
            $file = $j->configuration;
            $data = json_decode($file, true);
            $filename = $data['input_file_json']['fileName'];
            $local_path = public_path() . "/storage/jobs/" . $j->id . "/";

            if ($j->jobs_solvers == "1") {
                //$job_di= /home/ruoyuanluo/Executable_CFIEHFMM_serial/{JOB_ID}
                $job_dir = str_replace('{JOB_ID}', $j->id, $app->getAttribute("remote_dir"));
                $input_dir = $job_dir . "/INPUT/";
                $cmd = "";
            } else {
                $job_dir = str_replace('{JOB_ID}', $j->id, $app->getAttribute('converter_dir'));
                $input_dir = $job_dir . "/_INPUT/";
                $cmd = "";
            }

            foreach ($j->sshservers as $server) {
                $sftp = new SFTP($server->host, $server->port);
                if (!$sftp->login($server->username, $server->password)) {
                    exit('Login Failed');
                }
                $sftp->mkdir("$job_dir");
                $sftp->mkdir("$input_dir");
                $sftp->chdir("$input_dir");
                foreach($filename as $key => $value)
                {
                    if ($j->jobs_solvers == "1") {
                        if(strtolower($key) == 'mesh') {
                            $dir = $input_dir."/Meshes";
                            $sftp->mkdir("$dir");
                            $sftp->chdir("$dir");
                            $filePath =  $local_path.$value;
                            $file = fopen($filePath, 'r');
                            $fileName = basename($filePath);
                            $sftp->put($fileName, $file, 8);
                        } else {
                            $sftp->mkdir("$job_dir.'/INPUT'");
                            $sftp->chdir("$job_dir.'/INPUT");
                            $filePath =  $local_path.$value;
                            $file = fopen($filePath, 'r');
                            $fileName = basename($filePath);
                            $sftp->put($fileName, $file, 8);
                        }
                     }
                     else {                                  
                        if (strtolower($key) == 'input') {
                            $sftp->mkdir("$job_dir");
                            $sftp->chdir("$job_dir");
                            $filePath =  $local_path .$value;
                            $file = fopen($filePath, 'r');
                            $fileName = basename($filePath);
                            $sftp->put('input.input', $file, 8);
                        } else {
                            $dir = $input_dir."/".$key;
                            $sftp->mkdir("$dir");
                            $sftp->chdir("$dir");
                            $filePath = $local_path. $value;
                            $file = fopen($filePath, 'r');
                            $fileName = basename($filePath);
                            $sftp->put($fileName, $file, 8);
                        }
                     }
                }
                
                $j->progress = 'In Progress';
                $command = "qsub";
                $c = new Connection($server->server_name, $server->host . ":" . $server->port, $server->username, ["password" => $server->password]);
                if($j->jobs_solvers == "1"){
                    
                    //$dir = '/home/ruoyuanluo/_OUTPUT';
                    $dir = $app->getAttribute('converter_remote_dir');
                    $sftp->mkdir("$dir");
                    if($j->jobs_solvers == "1")
                    //dd([sprintf("cd /home/ruoyuanluo/Executable_CFIEHFMM_serial&& ./%s --config ./%s/INPUT/input.conf", Kernel::solver, $f->id)]);
                    $c->run([sprintf("%s -v FOO=%s solver.pbs", $command, $j->id)], function ($line2) {

                        echo ($line2);
                    }, 30);
                }
                else if($j->jobs_solvers == "2"){
                    $c->run([sprintf("%s  %s -o %s -e %s", $command,  Kernel::app, $dir . "/OutputStream.txt", $dir . "/ErrorStream.txt")], function ($line) use ($j) // qsub 
                    {
                        echo ($line);
                        $new_job = new remotejob();
                        $new_job->job_id  = $j->id;
                        $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
                        $new_job->save();
                        $j->save();
                    });
                }
            }
        }
    })->everyMinute();

        $schedule->call(function () {
            $command_template =
                "qstat -f %s | grep job_state | awk -F ' ' '{print \$NF}'";
            $pattern = "/^[0-9]+/";
            $remote_jobs = remotejob::where('job_state', '!=', 'E')->orderBy('created_at', 'desc')->get();

            foreach ($remote_jobs as $rj) {
                $command = sprintf($command_template, str_replace(array("\n", "\r"), '', $rj->remote_job_id));
                foreach ($rj->job->sshservers as $server) {
                    $c = new Connection($server->server_name, $server->host . ":" . $server->port, $server->username, ["password" => $server->password]);
                    $c->run([$command], function ($line) use ($server, $rj, $pattern) {
                        # get return from std out;
                        $result = $line.PHP_EOL;

                        # not found job id with qstat means job is  complete or delete;
                        if (str_contains($result, 'Unknown')) {
                            $j = $rj->job;
                            $this->download($j->id, $server);
                            $j->previous_progress = $j->progress;
                            $j->progress = "Completed";
                            $j->save();
                            $rj->job_state = "E";
                            $rj->save();
                        } else {
                            # found job id with qstat means job is not complete or delete;
                            $rj->job_state = str_replace(array("\n", "\r"), '', $result);
                            $rj->save();
                        }
                    });
                }
            }
        })->everyMinute();
    }

    private function download($jobID, $server)
    {
        $app = new App();

        if ($jobID && $server) {
            $this->sftp = new SFTP($server->host, $server->port);
            if (!$this->sftp->login($server->username, $server->password)) {
                exit('Login Failed');
            }

            //$remote_path= '/home/ruoyuanluo/Executable_CFIEHFMM_serial/OUTPUT/';
            $remote_path = $app->getAttribute("remote_path");
            //"public/storage/jobs/".$jobID."/output/solver_output/";
            $local_path = str_replace('{JOB_ID}', $jobID, $app->getAttribute("local_path"));

            $this->get($remote_path, $local_path);
            // $remote_path= 'home/ruoyuanluo/_OUTPUT/'
            $remote_path = $app->getAttribute("converter_remote_path");

            //$local_path= "public/storage/jobs/".$jobID."/output/"
            $local_path = str_replace('{JOB_ID}', $jobID, $app->getAttribute("converter_local_path"));
            $this->get($remote_path, $local_path);
        }
    }

    private function get($remote_path, $local_path)
    {
        $this->sftp->chdir($remote_path);
        $files =  $this->sftp->nlist(".", true);
        File::makeDirectory($local_path, 0755, true, true);

        foreach ($files as $file) {
            if ($file != ".." && $file != ".") {
                $a = dirname($file);
                if ($a != ".") File::makeDirectory($local_path . $a, 0755, true, true);
                $this->sftp->get($remote_path . $file, $local_path . $file);
            }
        }
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
