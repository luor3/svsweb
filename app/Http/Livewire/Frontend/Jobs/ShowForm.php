<?php

namespace App\Http\Livewire\Frontend\Jobs;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Job;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use Livewire\WithPagination;
use App\Http\Controllers\JobsController;
use App\Models\sshservers;
use phpseclib\Net\SFTP;
use \ZipStream\Option\Archive;
use \ZipStream\ZipStream;
use SSH;
use Collective\Remote\Connection;
class ShowForm extends Component
{

    use WithFileUploads;

    use WithPagination;

    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'frontend.jobs.show-form';
    

    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'jobs';
    

    /**
     * 
     * @var Job
     */
    public $job;
  
    public $permission;
    
    public $userID;
   

    public $pathName;

    public $server_id;

    /**
     * 
     * @var Job id
     */
    public $jobID = -1;

    /**
     * 
     * @var Category array
     */
    public $categories = []; 


    /**
     * 
     * @var File array
     */
    public $outputFiles;


    /**
     * 
     * @var File array
     */
    public $inputFiles;

    /*
    **
    **
    */
    public $job_status = "All Statuses";

    public $job_progress = "All Progresses";

    public $status; // temporary variable to record the status of job 

    public $uploadFields = [];

    public $Previosu_status; //used for recording previous progress


    /**
     * 
     * @var boolean
     */
    public $confirmingJobDeletion = false;

    /**
     * 
     * @var boolean
     */
    public $confirmingJobWithdraw = false;


    /**
     * 
     * @var boolean
     */
    public $confirmingJobRecover = false;

    /**
     * 
     * @var boolean
     */
    public $displayEditable = false;



    /**
     * 
     * @var array
     */
    public $outputFileJson = [];

    public $nameSearch = '';
    /**
     * 
     * @var array
     */
    public $inputFileJson = [];


    /**
     * 
     * @var array
     */
    public $jobAttr = [
        'name' => null,
        'description' => null,
        'status' => null,
        'category_id' => null,
        'description'=>null,
        'id'=>null   
    ];


    public $currentOrderProperty;


    public $currentOrder = 'asc';

    
    /**
     * 
     * @var integer
     */
    public $categorySearch = -1;


    /**
     * 
     * @var integer
     */
    public $pageNum = 5;


    /**
     * 
     * @var string
     */



    /**
     * 
     * @var array
     */
    protected $queryString = [
        'categorySearch' => ['except' => -1],
        'pageNum',
        'nameSearch' => ['except' => ''],
        'jobID' => ['except' => -1]
    ];


    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'jobAttr.name' => 'required|max:255|unique:jobs,name,'.$this->job->id,
            'jobAttr.category_id' => 'required|integer',
            'jobAttr.description' => 'required|max:1024',
            'jobAttr.status' => 'required|boolean',
        ];
    }


    /**
     * reset page number before updating category search
     * 
     * @return void
     */
    public function updatingCategorySearch()
    {
        $this->resetPage();
    }

    /**
     * reset page number before updating name search
     * 
     * @return void
     */
    public function updatingNameSearch()
    {
        $this->resetPage();
    }

    /**
     * reset page number before updating pageNum
     * 
     * @return void
     */
    public function updatingpageNum()
    {
        $this->resetPage();
    }


    /**
     * initialize the job page properties
     * @return void
     */
    public function mount()
    { 
        $this->pathName = request()->route()->getName();

        if($this->pathName != "jobs" && $this->pathName != "jobs.all") {
            $this->pathName = 'userprofile';
        }

        $categories = Category::all();
        foreach ($categories as $category)
        {
            $this->categories[$category->id] =  $category->name;
        }
        $user = auth()->user();
        $this->userID = $user->id;
        
        if($this->jobID !== -1)
        {
            $this->registerJob($this->jobID, false);
        }
            
    }


    /**
     * render the job
     * @return View Job section
     */
    public function render()
    {  
        $jobs = [];
        if(isset($this->job) && $this->jobID !== -1)
        {
            $data = Job::find($this->jobID)->toArray();
            $configuration = json_decode($data['configuration'],true);

            $this->inputFileJson =  $configuration['input_file_json'];
            (
            count($this->uploadFields) == count($this->inputFileJson['fileName'])-1)?
            $this->displayEditable = true : $this->displayEditable = false;  

        }
        else
        {            
            $jobs = Job::leftjoin('users', 'jobs.user','=','users.id');         
            if($this->categorySearch != -1)
            {
                $jobs = $jobs->Where('category_id', $this->categorySearch);
            }

            if($this->job_progress != "All Progresses"){
                $jobs = $jobs->Where('progress', $this->job_progress);
            }

            if($this->job_status != "All Statuses"){
                if($this->job_status == 'Yes'){
                    $this->status = 1;
                }
                else
                {
                    $this->status = 0;
                }
                $jobs = $jobs->Where('jobs.status', $this->status);
            }

            if(auth()->user()->role=='user'|| !$this->permission || $this->pathName===route('jobs')){
                $jobs = $jobs->where('jobs.user', $this->userID);
            }
            
            if(!is_null($this->currentOrderProperty) && $this->currentOrder !== '')
            {
                $jobs = $jobs -> orderBy($this->currentOrderProperty,$this->currentOrder);  
            }

            $jobs = $jobs->where('jobs.name', 'like', '%'.$this->nameSearch.'%');//->where('users.name', 'like', '%'.$this->nameSearch.'%');
            $jobs = $jobs -> paginate($this->pageNum,['jobs.*','users.name AS user_name']);
           
        }
        
        return view(self::COMPONENT_TEMPLATE,['jobs' => $jobs]);
    }


    /**
     * update job
     * 
     * @return void
     */
    public function update()
    {
        $this->validate();
    
        if (isset($this->job)) 
        { 
            $this->job->name = $this->jobAttr['name'];
            $this->job->description = $this->jobAttr['description'];
            $this->job->category_id = $this->jobAttr['category_id'];
            $this->job->status = $this->jobAttr['status'];

            try 
            {
                  
                foreach ($this->inputFiles as $fileType => $file)
                {
                    if(isset($this->inputFiles[$fileType]))
                    {
                        if(isset($input_file_json['fileName'][$fileType]))
                        {
                            Storage::disk('public')->delete($this->inputFileJson[$fileType]);
                        }       
                        $this->inputFileJson['fileName'][$fileType] = $file->getClientOriginalName();
                        $this->inputFileJson[$fileType] = $file->store('jobs/'.$this->job->id,'public');
                    }
                }
                
            } 

            catch (\Exception $e) 
            {
                session()->flash('flash.banner', 'Something Wrong while Updating Job');
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);;
            }
            $data = job::find($this->job->id)->toArray();
            $data['configuration'] = json_decode($data['configuration'],true);
            $data['configuration']['input_file_json'] = $this->inputFileJson;
            
            if(count($this->inputFileJson['fileName']) - 1 == count($this->uploadFields))
            {
                $this->job->status = 1;
                foreach($this->uploadFields as $fType => $extension)
                {
                    if(!array_key_exists($fType, $this->inputFileJson['fileName']))
                    {
                        $this->job->status = 0;
                        break;
                    }
                }

            }
            
            unset($this->inputFileJson);
        
            $this->job->configuration = json_encode($data['configuration']);

            $status = $this->job->save();           
            $this->emit('saved', $status);
        }
    }


    /**
     * delete Job
     * 
     * @return view
     */
    public function delete()
    {
        if ($this->job && $this->job->id) 
        {
            $deleted = $this->job->delete(); // this will also delete related files through ORM deleting hook function.
            $msg =  $deleted ? 'Job successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
                                                  
            if ($deleted) 
            {    
                if($this->pathName == 'userprofile')
                    return redirect()->route($this->pathName, ['currentModule' => "jobs"]);
                else
                    return redirect()->route($this->pathName);
            }
        }
    }


    /**
     * register Job for further operation (update and delete)
     * 
     * @return view
     */
    public function registerJob($job_id, $delete)
    {
        $this->job = Job::find($job_id);
        if(is_null($this->job))
        {
            abort(404);
        }
        if($delete)
        {
            $this->confirmingJobDeletion = true;
        }
        else
        {
            $this->jobID = $this->job->id;
            if (isset($this->job)) 
            {
                $this->jobAttr['name'] = $this->job->name;
                $this->jobAttr['description'] = $this->job->description;
                $this->jobAttr['category_id'] = $this->job->category_id;
                $this->jobAttr['status'] = $this->job->status;

                $configuration = json_decode($this->job->configuration, true);
                $this->uploadFields = $configuration["input_property_json"];
                foreach ($this->uploadFields as $fileType => $extension){
                    $this->inputFiles[$fileType] = null;
                }       
            } 
        }
      
    }

    
    /*
     * call after clicking "back" in job edit page
     * 
     * @return void
     */
    public function clearJob()
    {
        $this->job = null;
        $this->jobID = -1;
    }


    /**
     * change order status
     * 
     * @return void
     */
    public function jobOrder($property)
    {
        if($property === $this->currentOrderProperty)
        {
           $this->currentOrder = ($this->currentOrder === 'asc') ? 'desc' : (($this->currentOrder === 'desc') ? '' : 'asc');
        }
        else
        {
            $this->currentOrder = 'asc';
            $this->currentOrderProperty = $property;
        }
    }


    /**
     * call after clicking "Edit" in job page
     * 
     * @return redrect route 
     */
    public function redirecToJob($jobID)
    {   
        // $this->job = job::find($jobID);
        // $this->jobID = $jobID;
        // $this->mount();
    

        $parameter = [
            'currentModule' => "jobs",
            'jobID' => $jobID
        ];
        if($this->pathName == 'userprofile')
            return redirect()->route($this->pathName , $parameter);
        else
            return redirect()->route($this->pathName , ['jobID' => $jobID]);
    }

    /*
     * withdraw Job
     *
     */
    public function withdrawJob($jobID, $server_id)
    {
        
        $this->job = job::find($jobID);
        if($this->job->progress === 'Pending'||$this->job->progress === 'In Progress'){
            //$this->Previosu_status = $this->job->progress;
            $this->confirmingJobWithdraw = true;
        }
        else if($this->job->progress === 'Cancelled'){
            $this->confirmingJobWithdraw = false;
            $this->confirmingJobRecover = true; 
        }
        $this->$server_id = $server_id;
        
        
    }

    public function withdraw(){
        $this->job -> previous_progress = $this->job-> progress;
        $this->job-> progress = 'Cancelled';
        try {
                if ($this->job->remotejob && $this->job->remotejob->remote_job_id) {
                $remote_id =  $this->job->remotejob->remote_job_id;
                $pid_shell = sprintf("qsig -s SIGKILL %s" ,$remote_id );
                $server = sshservers::find($this->server_id);
                if ($server) {
                    $c = new Connection($server->server_name, $server->host.":".$server->port, $server->username,["password"=>$server->password]);
                    $c->run([
                        $pid_shell
                    ], function($line) use($c)
                    {
                        $c->run([
                            sprintf('kill %s', $line)
                        ], function($line2) {
                        });
                    }
                    );
                }
            }
        } catch(Exception $e) {
            dd($e->getMessage());
        }
        $this->job->save();
        return redirect()->route($this->pathName);
    }

    /*
     * Recover Job
     */
    public function recover(){
        $this->job-> progress = $this->job -> previous_progress;
        $this->job->save();
        return redirect()->route($this->pathName);

    }

    public function demoOrder($property)
    {
        if($property === $this->currentOrderProperty)
        {
           $this->currentOrder = ($this->currentOrder === 'asc') ? 'desc' : (($this->currentOrder === 'desc') ? '' : 'asc');
        }
        else
        {
            $this->currentOrder = 'asc';
            $this->currentOrderProperty = $property;
        }
    }

    public function downloadFile($jobID, $server_id, $isInput)
    {
        try {
            $this->job = job::find($jobID);
            if($this->job->progress == 'Completed' || $this->job->progress == 'Cancelled' ) {
                $output_name = sprintf("%d_output.zip", $jobID);
                $server = sshservers::find($server_id);
                $sftp = new SFTP($server->host, $server->port);
                if (!$sftp->login($server->username, $server->password)) {
                    exit('Login Failed');
                } 

                $path = '/home/ruoyuanluo/_OUTPUT/'.$jobID;
                //dd($path);
                $sftp->chdir($path);
                $files = $sftp->nlist(".");
                $local_path = "public";

                foreach ($files as $file) {
                    
                    if($file != ".." && $file != ".") {
                        $sftp->get(sprintf("%s/%s",$path,$file), $local_path);
                    }
                }
                return response()->streamDownload(function () use($output_name, $local_path, $files)
                {
                    $options = new Archive();
                    $options->setSendHttpHeaders(false);
                    $zip = new ZipStream( $output_name, $options);
                    foreach ($files as $file) {
                        if($file != ".." && $file != ".") {
                            $zip->addFileFromPath($file,$local_path);
                        }
                    }
                    $zip->finish();
                }, $output_name);
            }
        }
        catch(\Exception $e) {
            dd($e->getMessage());
        }
        
    }
}
