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


    // SSH::put($request->profile_photo_path, 'test');
    // SSH::run([
    //     'cd test', 'ls'
    // ], function($line)
    // {
    //     echo $line.PHP_EOL;
    // }

    // );
    //  dd($process);
    // $file = Job::find(1)->configuration;
    // $data = json_decode($file, true);
    // $filename = $data['input_file_json'];
    // $jason = json_decode($filename, true);
    // return $jason['materials'];
    // dd($jason);
    // dd(file_exists(__DIR__.'/../r4v6Oeboldn8Ggsr3SuU2VhtVnkBvtWgQXXu6fC9.txt'));
    // try{
    //     $file = fopen(__DIR__.'/../r4v6Oeboldn8Ggsr3SuU2VhtVnkBvtWgQXXu6fC9.txt', 'r');
    //     //dd(is_resource($file));
    // SSH::put($file, 'test');
    // }
    // catch(Exception $e)
    // {
    //     dd($e->getMessage());
    // }
    //$sftp = new SFTP('ece-e3-516-f.eng.umanitoba.ca');
    //$sftp = new SFTP('130.179.128.26');
    //if (!$sftp->login('mohamm60', '1ldg4DC2p5')) {
        //if (!$sftp->login('ruoyuanluo', 'ruoyuanluo123')) {
        //exit('Login Failed');
    //}  
    //DB::enableQueryLog();
    //$files = Job::where('progress','=','pending')->orderBy('created_at')->get();
    $files = Job::where('progress','=','pending')->orderBy('created_at','desc')->get();
    //$files = Job::all();
    //dd($files);
    foreach($files as $f){
        foreach($f->sshservers as $server){ 
        
            $sftp = new SFTP($server->host, $server->port);
            if (!$sftp->login($server->username, $server->password)) {
                exit('Login Failed');
            } 
            $file = $f->configuration;
            $data = json_decode($file, true);
            $filename = $data['input_file_json'];
            //$dir = $f->id;
            //$dir = 'solver/__INPUT';
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
            $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password]);
            $c->run([$command." ".$app], function($line) use ($f)
            {
                echo $line.PHP_EOL;
                $new_job = new remotejob();
                $new_job->job_id  = $f->id;
                $new_job->remote_job_id = str_replace(array("\n", "\r"), '', $line.PHP_EOL);
                $new_job->save();
                $f->save();
    
            });
        }
        //dd($f->progress);
    }
});
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