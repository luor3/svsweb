<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use phpseclib\Net\SFTP;
use App\Models\remotejob;
use App\Models\Job;
use Collective\Remote\RemoteFacade as SSH;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
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
            $sftp = new SFTP('130.179.128.26');
            //if (!$sftp->login('mohamm60', '1ldg4DC2p5')) {
            if (!$sftp->login('ruoyuanluo', 'ruoyuanluo123')) {
                exit('Login Failed');
            }  
            //DB::enableQueryLog();
            //$files = Job::where('progress','=','pending')->orderBy('created_at')->get();
            $files = Job::where('progress','=','pending')->orderBy('created_at','desc')->get();
            //$files = Job::all();
            //dd($files);
            foreach($files as $f){
                $file = $f->configuration;
                $data = json_decode($file, true);
                $filename = $data['input_file_json'];
                //$dir = $f->id;
                //$dir = 'solver/__INPUT';
                $dir = 'home/ruoyuanluo/__INPUT';
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
                //dd($filename);
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
                    $sftp->put($fileName, $file, 8);
        
                }
                $sftp->chdir('..');
                
                $f->progress = 'In Progress';
                $app = "a.sh";
                $command = "qsub";
                SSH::run([sprintf("%s %s",$command, $app)], function($line) use ($f)
                {
                    echo $line.PHP_EOL;
                    $new_job = new remotejob();
                    $new_job->job_id  = $f->id;
                    $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
                    $new_job->save();
                    $f->save();
         
                });
                //dd($f->progress);
            }
        })->everyMinute();
        $schedule->call(function () {
            $command_template =
            "qstat -f %s | grep job_state | awk -F ' ' '{print \$NF}'";
           $jobs = remotejob::where('job_state','!=','E')->orderBy('created_at','desc')->get();
            echo $jobs;
            foreach($jobs as $j) {
                $command = sprintf($command_template, str_replace(array("\n", "\r"), '', $j->remote_job_id));
                SSH::run([$command], function($line) use ($j)
                {
                    $j->job_state = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
                    $j->save();
               });
            }
            
            //dd($f->progress);
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
