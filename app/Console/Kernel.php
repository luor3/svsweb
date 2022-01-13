<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use phpseclib\Net\SFTP;
use App\Models\Job;

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
            $sftp = new SFTP('ece-e3-516-f.eng.umanitoba.ca');
            if (!$sftp->login('mohamm60', '1ldg4DC2p5')) {
                exit('Login Failed');
            }
            //DB::enableQueryLog();
            //$files = Job::where('progress','=','pending')->orderBy('created_at')->get();
            $files = Job::where('progress', '=', 'pending')->orderBy('created_at', 'desc')->get();
            //$files = Job::all();
            //dd($files);
            foreach ($files as $f) {
                $file = $f->configuration;
                $data = json_decode($file, true);
                $filename = $data['input_file_json'];
                //$dir = $f->id;
                $dir = 'solver/_INPUT';
                $sftp->mkdir("$dir");
                $sftp->chdir("$dir");
                if ($filename['materials']) {
                    $filePath = storage_path('app/' . $filename['materials']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $material_dir = 'materials';
                    $sftp->mkdir($material_dir);
                    $sftp->put($material_dir.'/'.$fileName, $file, 8);
                }
                if ($filename['meshes']) {
                    $filePath = storage_path('app/' . $filename['meshes']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $mesh_dir = 'meshes';
                    $sftp->mkdir($mesh_dir);
                    $sftp->put($mesh_dir.'/'.$fileName, $file, 8);
                }
                if ($filename['waves']) {
                    $filePath = storage_path('app/' . $filename['waves']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $wave_dir = 'waves';
                    $sftp->mkdir($wave_dir);
                    $sftp->put($wave_dir.'/'.$fileName, $file, 8);
                }
                //dd($filename);
                if (isset($filename['mlr'])) {
                    $filePath = storage_path('app/' . $filename['mlr']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $sftp->put($fileName, $file, 8);
                }
                if (isset($filename['FieldPoints'])) {
                    $filePath = storage_path('app/' . $filename['FieldPoints']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $sftp->put($fileName, $file, 8);
                }
                if ($filename['input']) {

                    $sftp->chdir("..");

                    $filePath = storage_path('app/' . $filename['input']);
                    $file = fopen($filePath, 'r');
                    $fileName = basename($filePath);
                    $sftp->put($fileName, $file, 8);
                }
                $sftp->chdir('..');

                $f->progress = 'In Progress';
                $f->save();
                //dd($f->progress);
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
