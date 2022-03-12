<?php


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

$app = new App();
$files = Job::where('progress', '=', 'pending')->orderBy('created_at', 'desc')->get();

foreach ($files as $f) {
    $file = $f->configuration;
    $data = json_decode($file, true);
    $filename = $data['input_file_json'];
    $local_path = public_path()."/storage/jobs/".$f->id."/";
    
    $coverted_mesh_path = $local_path."/converter_meshes/";
    $solver_mesh_path = $local_path."/solver_meshes/";

    //$remote_dir = '/home/ruoyuanluo/Executable_CFIEHFMM_serial/'.$f->id;
    $remote_dir = str_replace('{JOB_ID}', $f->id, $app->getAttribute("remote_dir"));

    $solver_files = File::files($local_path);
    $solver_mesh_files = File::files($solver_mesh_path);


    foreach ($f->sshservers as $server) {
        $sftp = new SFTP($server->host, $server->port);
        if (!$sftp->login($server->username, $server->password)) {
            exit('Login Failed');
        }
        $sftp->mkdir("$remote_dir");
        $sftp->mkdir("$remote_dir"."/INPUT");
        $sftp->chdir("$remote_dir"."/INPUT");
        //echo("$remote_dir"."/INPUT");
        $filePath = storage_path('app/'.$filename['input']);
        $file = fopen($filePath, 'r');
        $inputfile_name = basename($filePath);
        foreach($solver_files as $sf){
            if($sf->getFilename() != $inputfile_name) {
                //echo($sf->getFilename());
                $sf_file = fopen($sf->getPathname(), 'r');
                $sftp->put($sf->getFilename(), $sf_file, 8);
            }
        }
             
        $sftp->mkdir("$remote_dir"."/INPUT/Meshes");
        $sftp->chdir("$remote_dir"."/INPUT/Meshes");
        foreach($solver_mesh_files as $sf){
            $sf_file = fopen($sf->getPathname(), 'r');
            $sftp->put($sf->getFilename(), $sf_file, 8);
        }


        //$converted_dir = '/home/ruoyuanluo/'.$f->id;
        $converted_dir = str_replace('{JOB_ID}', $f->id, $app->getAttribute('converter_dir'));
        $sftp->mkdir("$converted_dir");
        $input_dir = $converted_dir.'/_INPUT/';
        $sftp->mkdir("$input_dir");
        $sftp->chdir("$input_dir");
        if ($filename['materials']) {
            $material_dir = $input_dir.'/materials/';
            $sftp->mkdir("$material_dir");
            $sftp->chdir("$material_dir");
            $filePath = storage_path('app/'.$filename['materials']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put($fileName, $file, 8);
        }
        if ($filename['meshes']) {
            $dir =  $input_dir.'/meshes/';
            $sftp->mkdir("$dir");
            $sftp->chdir("$dir");
            $filePath = storage_path('app/'. $filename['meshes']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put($fileName, $file, 8);
        }
        if ($filename['waves']) {
            $dir = $input_dir.'/waves/';
            $sftp->mkdir("$dir");
            $sftp->chdir("$dir");
            $filePath = storage_path('app/'.$filename['waves']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put($fileName, $file, 8);
        }
        if (isset($filename['mlr'])) {
            $dir = $input_dir.'/mlr/';
            $sftp->mkdir("$dir");
            $sftp->chdir("$dir");
            $filePath = storage_path('app/'.$filename['mlr']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put($fileName, $file, 8);
        }
        if (isset($filename['FieldPoints'])) {
            $dir = $input_dir.'/FieldPoints/';
            $sftp->mkdir("$dir");
            $sftp->chdir("$dir");
            $filePath = storage_path('app/'. $filename['FieldPoints']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put($fileName, $file, 8);
        }
        if ($filename['input']) {
            $sftp->chdir("..");
            $sftp->chdir("..");
            $filePath = storage_path('app/'.$filename['input']);
            $file = fopen($filePath, 'r');
            $fileName = basename($filePath);
            $sftp->put('input.input', $file, 8);
        }
    
        $f->progress = 'In Progress';
        $command = "qsub";
        
        $c = new Connection($server->server_name, $server->host . ":" . $server->port, $server->username, ["password" => $server->password]);
        //$dir = '/home/ruoyuanluo/_OUTPUT';
        $dir = $app->getAttribute('converter_remote_dir');
        $sftp->mkdir("$dir");

        //dd([sprintf("cd /home/ruoyuanluo/Executable_CFIEHFMM_serial&& ./%s --config ./%s/INPUT/input.conf", Kernel::solver, $f->id)]);
        $c->run([sprintf("cd %s; ./%s --config ./%s/INPUT/input.conf", str_replace('{JOB_ID}','',$app->getAttribute("remote_dir")),Kernel::solver, $f->id)], function($line2){

            echo($line2);
        },30);

        $c->run([sprintf("%s  %s -o %s -e %s", $command,  Kernel::app, $dir."/OutputStream.txt", $dir."/ErrorStream.txt")], function ($line) use ($f) // qsub 
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

?>