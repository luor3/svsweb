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

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    public const app = "run.pbs";

    public const out = "out.txt";

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

            $files = Job::where('progress', '=', 'pending')->orderBy('created_at', 'desc')->get();

            foreach ($files as $f) {
                foreach ($f->sshservers as $server) {
                    $sftp = new SFTP($server->host, $server->port);
                    if (!$sftp->login($server->username, $server->password)) {
                        exit('Login Failed');
                    }
                    $file = $f->configuration;
                    $data = json_decode($file, true);
                    $filename = $data['input_file_json'];

                    $dir = '/home/ruoyuanluo/'.$f->id;
                    $sftp->mkdir("$dir");
                    $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/';
                    $sftp->mkdir("$dir");
                    $sftp->chdir("$dir");
                    if ($filename['materials']) {
                        $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/materials/';
                        $sftp->mkdir("$dir");
                        $sftp->chdir("$dir");
                        $filePath = storage_path('app/' . $filename['materials']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
                        
                        
                    }
                    if ($filename['meshes']) {
                        $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/meshes/';
                        $sftp->mkdir("$dir");
                        $sftp->chdir("$dir");
                        $filePath = storage_path('app/' . $filename['meshes']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
                    }
                    if ($filename['waves']) {
                        $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/waves/';
                        $sftp->mkdir("$dir");
                        $sftp->chdir("$dir");
                        $filePath = storage_path('app/' . $filename['waves']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
                    }
                    if (isset($filename['mlr'])) {
                        $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/mlr/';
                        $sftp->mkdir("$dir");
                        $sftp->chdir("$dir");
                        $filePath = storage_path('app/' . $filename['mlr']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
                    }
                    if (isset($filename['FieldPoints'])) {
                        $dir = '/home/ruoyuanluo/'.$f->id.'/_INPUT/FieldPoints/';
                        $sftp->mkdir("$dir");
                        $sftp->chdir("$dir");
                        $filePath = storage_path('app/' . $filename['FieldPoints']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
                    }
                    if ($filename['input']) {

                        $sftp->chdir("..");
                        $sftp->chdir("..");
                        $sftp->chdir("..");
                        $filePath = storage_path('app/' . $filename['input']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put('input.input', $file, 8);
                    }
                    //$sftp->chdir(' ~');

                    $f->progress = 'In Progress';
                    $command = "qsub";
                    
                    $c = new Connection($server->server_name, $server->host . ":" . $server->port, $server->username, ["password" => $server->password]);
                    $dir = '/home/ruoyuanluo/_OUTPUT';
                    $sftp->mkdir("$dir");
                   
                    //$c->run([sprintf("%s -v qsub-args=%s %s",$command, $args, Kernel::app)], function($line) use ($f)
                    $c->run([sprintf("%s  %s", $command,  Kernel::app)], function ($line) use ($f) // qsub 
                    {
                        echo ($line);
                        $new_job = new remotejob();
                        $new_job->job_id  = $f->id;
                        $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line . PHP_EOL);
                        $new_job->save();
                        $f->save();
                    });
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
                        $result = $line . PHP_EOL;

                        # not found job id with qstat means job is  complete or delete;
                        if (str_contains($result, 'Unknown')) {
                            //dd($rj->job->);
                            $j = $rj->job;
                            //dd($j);
                            $this->download($j->id,$server);
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
        if($jobID && $server){
            
            $sftp = new SFTP($server->host, $server->port);
            if (!$sftp->login($server->username, $server->password)) {
                exit('Login Failed');
            }

            $path = '/home/ruoyuanluo/_OUTPUT/';
            $sftp->chdir($path);
            $files = $sftp->nlist(".");
            $local_path = "public/storage/jobs/".$jobID."/output/";
            File::makeDirectory($local_path,0755,true,true);

            foreach ($files as $file) {
                if ($file != ".." && $file != ".") {
                    $sftp->get( $path.$file, $local_path.$file);
                }
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
