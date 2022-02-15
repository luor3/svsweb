<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use phpseclib\Net\SFTP;
use App\Models\remotejob;
use App\Models\Job;
use Collective\Remote\RemoteFacade as SSH;
use Collective\Remote\Connection;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    public const app = "run.pbs";

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
           
            $files = Job::where('progress','=','pending')->orderBy('created_at','desc')->get();
           
            foreach($files as $f){
                foreach($f->sshservers as $server){             
                    $sftp = new SFTP($server->host, $server->port);
                    if (!$sftp->login($server->username, $server->password)) {
                        exit('Login Failed');
                    } 
                    $file = $f->configuration;
                    $data = json_decode($file, true);
                    $filename = $data['input_file_json'];
                    
                    $dir = '/home/ruoyuanluo/_INPUT';
                    $sftp->mkdir("$dir");
                    $sftp->chdir("$dir");
                    
            
                    if($filename['materials']){
                        $filePath = storage_path('app/'.$filename['materials']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
            
                    }if($filename['meshes']){
                        $filePath = storage_path('app/'.$filename['meshes']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
            
                    }if($filename['waves']){
                        $filePath = storage_path('app/'.$filename['waves']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);

                    }
                    if(isset($filename['mlr'])){
                        $filePath = storage_path('app/'.$filename['mlr']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);

                    }if(isset($filename['FieldPoints'])){
                        $filePath = storage_path('app/'.$filename['FieldPoints']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put($fileName, $file, 8);
            
            
                    }
                    if($filename['input']){
                        
                        $sftp->chdir("..");
                        $filePath = storage_path('app/'.$filename['input']);
                        $file = fopen($filePath, 'r');
                        $fileName = basename($filePath);
                        $sftp->put('input.input', $file, 8);
            
                    }
                    $sftp->chdir('..');
                    
                    $f->progress = 'In Progress';
                    $command = "qsub";
                    $args = "1"; ##todo
                
                    $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password]);
                    $c->run([sprintf("%s -v qsub-args=%s %s",$command, $args, Kernel::app)], function($line) use ($f)
                    {
                        echo($line);
                        $new_job = new remotejob();
                        $new_job->job_id  = $f->id;
                        $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
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
            $remote_jobs = remotejob::where('job_state','!=','E')->orderBy('created_at','desc')->get();
            
            foreach($remote_jobs as $rj) {
                $command = sprintf($command_template, str_replace(array("\n", "\r"), '',$rj->remote_job_id ));
                foreach($rj->job->sshservers as $serverj) {
                    $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password]);
                    $c->run([$command], function($line) use ($server, $rj,$pattern)
                    {
                        # get return from std out;
                        $result = $line.PHP_EOL;
                        
                        # not found job id with qstat means job is  complete or delete;
                        if (str_contains($result, 'Unknown')) {
                            if (preg_match($pattern, $rj->remote_job_id, $real_remote_id)) {
                            #check job is complete with file like a.sh.o$job_id 
                                $filename = Kernel::app.".o".strval($real_remote_id[0]);
                                $cc = "ls ".$filename;
                                $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password]);

                                $c->run( [$cc], function($l) use($rj,$filename) {
                                    if(strcmp($l.PHP_EOL,$filename)) {
                                        
                                        $j = job::find($rj->job_id);
                                    
                                        $j->previous_progress = $j->progress;
                                        $j->progress = "Completed";
                                        
                                        $j->save();
                                        $rj->job_state = "E";
                                        $rj->save();
                                    } else {
                                        $j = job::find($rj->job_id);
                                        $j->previos_progress = $j->progress;
                                        $j->progress = "Canceled";
                                        $j->save();
                                        $rj->job_state = "S";
                                        $rj->save();
                                    }
                                });
                            }
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
