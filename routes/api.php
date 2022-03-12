<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Models\User;
use App\Models\Job;
use App\Models\remotejob;
use App\Models\Settings;
use Hamcrest\Type\IsResource;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SFTP;
use Collective\Remote\RemoteFacade as SSH;
use Collective\Remote\Connection;
use App\Models\App;
use App\Console\Kernel;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users', function (Request $request) {

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
                        $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line . PHP_EOL);
                        $new_job->save();
                        $j->save();
                    });
                }
            }
        }
}
);
Route::get('/users1', function (Request $request) {
        $command_template =
        "qstat -f %s | grep job_state | awk -F ' ' '{print \$NF}'";
       $jobs = remotejob::where('job_state','!=','E')->orderBy('created_at','desc')->get();
        echo $jobs;
        foreach($jobs as $j) {
            $command = sprintf($command_template, str_replace(array("\n", "\r"), '', $j->remote_job_id));
            SSH::run([$command], function($line) use ($j)
            {
                echo $j;
                $j->job_state = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
                echo $j;
                $j->save();
           });
        }


        // http://phpseclib.sourceforge.net/sftp/examples.html#put
        /* SSH Put Method => works
        $filePath = __DIR__.'/../composer.lock';
        SSH::put($filePath, $fileName);
        die;
        */



        //$sftp = new SFTP('130.179.128.26');
        //if (!$sftp->login('ruoyuanluo', 'ruoyuanluo123')) {
         //   exit('Login Failed');
       // }
        /*
         *  Get Method => works
         */
        //echo $sftp->get('Testing1.txt');
        //echo $sftp->get('main.out');

        /* Put Method 1 => works
        $filePath = __DIR__.'/../Procfile';
        $file = fopen($filePath, 'r');
        $fileName = basename($filePath);
        $sftp->put($fileName, $file, 8);
        */

        /* Put Method 2 => works
        $filePath = __DIR__.'/../composer.json';
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $fileName = basename($filePath);
            $sftp->put($fileName, $content, 8);
        }
         */
});

Route::get('/jobs/{id}', function (Request $request, $id) {
    try {
        $shell_template = Settings::where('name','=','find_solver')->first();
        // dd($shell->value);
        $shell = sprintf($shell_template->value, $id);
        // $id);
        SSH::run([
            $shell
        ], function($line)
        {
            echo $line.PHP_EOL;
        }

        );

    } catch(Exception $e) {
        //dd($e->getMessage());
    }
});

Route::delete('/jobs/{id}', [JobsController::class, 'cancel']);