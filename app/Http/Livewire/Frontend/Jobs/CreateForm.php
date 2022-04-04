<?php

namespace App\Http\Livewire\Frontend\Jobs;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\sshservers;

use App\Models\jobs_sshservers;
use Collective\Remote\Connection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\solvers;

use Exception;

class CreateForm extends Component
{

    use WithFileUploads;

    protected $listeners = ['click-out' => 'updateConfirmingJobDeletion'];
    /**
     * 
     * @var string
     */
    public $user;
    
    /**
     * 
     * @var string
     */
    public $name;
    
    /**
     * 
     * @var string
     */
    public $description;
    
    /**
     * 
     * @var string
     */
    public $configuration;
    
    /**
     * 
     * @var integer
     */
    public $category_id;

     /**
     * 
     * @var integer
     */
    public $jobs_solvers;

    public $confirmingJobDeletion = false;

     /**
     * 
     * @var integer
     */
    public $sshserver_id = '';

    /**
     * 
     * @var file
     */
    public $plot_file;


    /**
     * 
     * @var file
     */
    public $input_file;

      /**
     * 
     * @var file
     */
    public $configuration_file;
      /**
     * 
     * @var file
     */
    public $excitation_file;
      /**
     * 
     * @var file
     */
    public $mesh_file;
      /**
     * 
     * @var file
     */
    public $material_file;


    /**
     * 
     * @var file array
     */
    public $input_files = [];


    /**
     * for displying only
     * @var Category array
     */
    public $categories = [];


    /**
     * for displying only
     * @var Category array
     */
    public $solvers = [];

     /**
     * for displying only
     * @var sshservers array
     */
    public $sshservers;


    public $inputfilename = [];

    /**
     * 
     * @var boolean
     * default is 1
     */
    public $status = 1;


    /**
     * 
     * @var boolean
     */
    public $next = false;
    
    public $server_name = '';
    public $host = '';
    public $port = '';
    public $username = '';
    public $password = '';

    

    public $uploadFields = [];


    /**
     * 
     * @var Job
     */
    public $job;
    

    
    /**
     * 
     * @var boolean
     */
    public $displayEditable;


    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'frontend.jobs.create-form';
    

    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'userprofile';


    /**
     * @var string Redirect to itself
     */
    const FAIL_ROUTE = 'userprofile';

    public function updateConfirmingJobDeletion()
    {
            
        return redirect()->route(self::FAIL_ROUTE);
    }


    /**
     * initilize properties
     * 
     * @return void
     */
    public function mount()
    {
        $categories = Category::all();
        $this->sshservers = [];
        foreach ($categories as $category)
        {
            $this->categories[$category->id] =  $category->name;
        }
        if(count($categories) != 0)
            $this->category_id = $categories[0]->id; 
        $servers = sshservers::where("active", "=","1")->get();
        foreach($servers as $server) {
            $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password], null, 2);
            $commands =[ "top -b -n 1 | head -n 4"];
            $cpu= '';
            $memory = "";
            try{
                $c->run($commands, function($line) use (&$cpu) {
                    $output = explode("\n" ,$line);
                    $cpu =  explode(" " ,substr($output[2],7,strlen($output[2])-7))[2];
                });
                $c->run(["free -k"], function($line) use (&$memory) {
                    $output = explode("\n" ,$line);
                    $memorys =  preg_split("/[ ]+/" ,$output[1]);
                    $memory = number_format($memorys[2]/ $memorys[1] * 100,2)."%";
                });
            }
            catch(Exception $e){
                $this->confirmingJobDeletion = true;
            }
           array_push( $this->sshservers, array(
                "sshserver" => $server,
                "cpu" => $cpu,
                "memory" => $memory,
           ));
        }
        if(!count($servers)){
            $this->sshserver_id = "custom";
        }       
        if(count( $this->sshservers)>0)
        {
            $this->sshserver_id = $this->sshservers[0]['sshserver']['id'];
        }

        $this->solvers = solvers::all();
        if(count( $this->solvers) > 0)
            $this->jobs_solvers = $this->solvers[0]->id; 
    }


    /**
     * render job page
     * 
     * @return view jobs section
     */
    public function render()
    {
        return view(self::COMPONENT_TEMPLATE);
    }


    /**
     * add job page
     * 
     * @return void
     */
    public function add(Request $request)
    {
        
        $this->validate([
            'input_files.*' => 'required|file',
            'status' => 'required|boolean',
        ]);

        $input_json = [
            'fileName' => []
        ];
        
        $input_json = json_decode($this->job->configuration,true);

        
        foreach ($this->input_files as $type => $file)
        {
            $fake_path = explode("/",$this->inputfilename[$type]);
            $input_json['input_file_json']['fileName'][$type] = end($fake_path);
            $dir =  'public/jobs/' . $this->job->id;
            $outDir = $dir. '/_OUTPUT';

            for ($i = 0; $i < count($fake_path) - 1 ; $i++) {
                $dir .= '/' . $fake_path[$i];
            }
              
            File::makeDirectory($dir,0755,true,true);
            Storage::disk('local')->makeDirectory($outDir);
            $input_json['input_file_json'][$type] = $file->storeAs($dir, end($fake_path));
        }

        $input_json = json_encode($input_json);

        $this->job->configuration = $input_json;
        $this->job->status = $this->status;

        $server_id = $this->sshserver_id;
        if(!strcmp($server_id,"custom")) {
            try {
                $c = new Connection($this->server_name, $this->host.":".$this->port, $this->username,["password"=>$this->password]);
                $command = "vmstat";
                $that = $this;
                $c->run([$command], function($line) use ($that) {
                    $ssh_server = new sshservers();
                    $ssh_server->server_name = $that->server_name;
                    $ssh_server->host = $that->host;
                    $ssh_server->port = $that->port;
                    $ssh_server->username = $that->username;
                    $ssh_server->password = $that->password;
                    $ssh_server->save();
                    $server_id = $ssh_server->id;
                    $jobs_ssh_server = new jobs_sshservers();
                    $jobs_ssh_server->job_id = $that->job->id;
                    $jobs_ssh_server->sshserver_id = $server_id;
                    $status = $jobs_ssh_server->save();
                    $status = $that->job->save();

                    $msg =  $status ? 'job successfully created!' : 'Ooops! Something went wrong.';
                    $flag = $status ? 'success' : 'danger';
        
                    session()->flash('flash.banner', $msg);
                    session()->flash('flash.bannerStyle', $flag);
                    
                    if ($status) 
                    {    
                        return redirect()->route(self::REDIRECT_ROUTE, ['currentModule' => "jobs"]);
                    }
                });
            } catch(\Exception $e) {
                session()->flash('flash.banner', $e->getMessage());
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::FAIL_ROUTE);
            }
        
        } else {
            $jobs_ssh_server = new jobs_sshservers();
            $jobs_ssh_server->job_id = $this->job->id;
            $jobs_ssh_server->sshserver_id = $server_id;
            $status = $jobs_ssh_server->save();
            $status = $this->job->save();


            $msg =  $status ? 'job successfully created!' : 'Ooops! Something went wrong.';
            $flag = $status ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($status) 
            {    
                return redirect()->route(self::REDIRECT_ROUTE, ['currentModule' => "jobs"]);
            }
        }

    }


    public $input_file_data = "";
    /**
     * register new job
     * 
     * @return void
     */
    public function registerJob(Request $request)
    {    
        $data = $this->validate([
            'name'=>'required|max:255',  
            'description' => 'required|max:255',
            'category_id' => 'required|integer',
            'jobs_solvers' => 'required|integer',
            'description' => 'required|max:255',
            'input_file' =>'required|file'  
        ]);
        $user = auth()->user();
        $data['user'] = $user->id;
       

        try 
        {  
            if($this->jobs_solvers==1) {
                $this->readFileFromInputConf($this->input_file->getRealPath());
            }
            else {
                $this->readFileFromInputInput($this->input_file->getRealPath());
            } 
            $this->next = true;
            $input_file_json = '{ "fileName" : [] }';
            $map = [
            'input_file_json'=>$input_file_json,
            'input_property_json'=>$this->uploadFields,
           ];
           $data['configuration'] = json_encode($map);

        } 
        catch (\Exception $e) 
        {
            session()->flash('flash.banner', $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::FAIL_ROUTE);
        }
        unset($data['input_file']);

        $this->job = job::create($data);
        $msg =  $this->job ? 'Job successfully created!' : 'Ooops! Something went wrong.';
        $flag = $this->job ? 'success' : 'danger';
    
        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        $this->EditDir($this->input_file->getRealPath(), $this->job->id);
        if (!$this->job) 
        {    
            return redirect()->route(self::FAIL_ROUTE);
        }
        $this->initInputFiles();


        $store_path = 'public/jobs/'.$this->job->id;
        Storage::disk('local')->makeDirectory($store_path);

        $originalname = explode('.',$this->input_file->getClientOriginalName());
        $originalname[0] = 'input';
        $originalname = implode('.', $originalname);
       
        if ($this->job->jobs_solvers == "1") {
            //$job_dir= /home/ruoyuanluo/Executable_CFIEHFMM_serial/{JOB_ID}
            $input_file_path = $this->input_file->storeAs($store_path.'/INPUT', $originalname);
            
            // TODO can be changed in future, put input.conf under jobID folder
            $this->input_file->storeAs($store_path, $originalname);

        } else {
            $input_file_path = $this->input_file->storeAs($store_path, $originalname);

        }
        $input_json = json_decode($this->job->configuration,true);
        $fileName_json = json_decode($input_json['input_file_json'] , true);
        $fileName_json["fileName"]["input"] = $originalname;
        $input_json['input_file_json'] = $fileName_json;

        $input_json['input_file_json']['input'] = $input_file_path;
        $this->job->configuration = json_encode($input_json);
        $this->job->save();

    }



    /**
     * read and parse input file
     * 
     * @return void
     */
    private function readFileFromInputInput($path)
    {
        $myfile = fopen($path, "r") or die("Unable to open file!");
        $pattern = "/^(_INPUT)/i";
        $lineNum = 1;
        while(! feof($myfile))
        {
            $line = fgets($myfile);
            if(preg_match($pattern, $line))
            {
                $inputProperties = explode("/",$line);
                if(count($inputProperties) != 3)
                {
                    throw new \Exception('Cannot parse input parameter at line '.$lineNum); 
                }
                $fileType = $inputProperties[1];
                $inputProperties = explode(".",trim($inputProperties[2]," \r\n\t"));
                if(count($inputProperties) < 2)
                {
                    throw new \Exception('Cannot parse file extension at line '.$lineNum); 
                }
                $fileExtension = end($inputProperties);
                $this->uploadFields[$fileType] = $fileExtension;
                $this->inputfilename[$fileType] = trim($line);
            }
            $lineNum++;
        }
        fclose($myfile);
    } 

    private function EditDir($path, $id)
    {
        $myfile = fopen($path,'r') or die("Unable to open file!");
        $remove_comment_pattern = "/^\@/i";
        $remove_comment_pattern2 = "/Output Directory/";
        while(! feof($myfile))
        {
            $line = fgets($myfile);
            if(preg_match($remove_comment_pattern, $line)){
                $lines[] = $line;
            }
            else{
                if(preg_match($remove_comment_pattern2, $line)){
                    $inputProperties = explode(":",$line);
                    //dd($inputProperties[1]);
                    $line = str_replace("\n",$id."/\n", $line);
                    //dd($line);
                }
                $lines[] = $line;
                
            }
        }
        $new_content = implode('',$lines);
        //dd($new_content);
        file_put_contents($path, $new_content);

        
    }
    /**
     * read and parse input file
     * 
     * @return void
     */
    private function readFileFromInputConf($path)
    {
        $myfile = fopen($path,'r') or die("Unable to open file!");
        $remove_comment_pattern = "/^\@/i";
        $input_pattern2 = "/File/i";
        $input_directory = "/^Input Directory/i";
        $lineNum = 1;
        $mesh_path = '';
        while(! feof($myfile))
        {
            $line = fgets($myfile);
            if( !trim($line)=='' && !preg_match($remove_comment_pattern, $line)){
                $inputProperties = explode(":",$line);
                if(count($inputProperties) != 2)
                {
                    throw new \Exception('Cannot parse input parameter at line '.$lineNum); 
                }
                if(preg_match($input_pattern2, $line))
                {
                    $extensions = explode('.', $inputProperties[1]);
                    $extension = end($extensions); 
                    $files = explode(" ", $inputProperties[0]);
                    $file = trim(reset($files));
                    $this->uploadFields[$file] = $extension;
                    if($file == "Mesh") {
                        $mesh_path .= trim($inputProperties[1]);
                    }
                    else 
                    {
                        $this->inputfilename[$file] = trim($inputProperties[1]);
                    }
                }
                else if(preg_match($input_directory, $line)) 
                {
                    $mesh_path = trim($inputProperties[1]) . $mesh_path;
                    $this->inputfilename[$file] = $mesh_path;
                }
            }
            $lineNum++;
        }   
        
        //dd($this->inputfilename);
        fclose($myfile);
    } 


    /**
     * initilize inputs file upload fields
     * 
     * @return void
     */
    private function initInputFiles()
    {
        if(isset($this->uploadFields))
        {
            foreach ($this->uploadFields as $fileType => $extension){
                $this->input_files[$fileType] = null;
            }
        }
    }


}
