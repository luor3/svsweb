<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use SSH;

class JobsController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'jobs.show';
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
        );
    }

    public function cancel(Request $request, $id)
    {
        $job = job::find($id);
        if($job->progress === 'Pending'||$this->job->progress === 'In Progress'){
            $job-> progress = 'Cancelled';
            try {
                $shell_template = Settings::where('name','=','find_solver')->first();
                // dd($shell->value);
                $pid_shell = sprintf($shell_template->value, $id);
                // $id);
                SSH::run([
                    $pid_shell
                ], function($line)
                {
                    echo $line;
                    SSH::run([
                        sprintf('kill %s', $line)
                    ], function($line2) {
                        
                    });
                }
                );
        
            } catch(Exception $e) {
                dd($e->getMessage());
            }
            $job->save();
        }
    }

}
