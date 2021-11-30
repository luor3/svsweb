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



    public $uploadFields = [];

    /**
     * 
     * @var File
     */
    public $plotFile;


    /**
     * 
     * @var boolean
     */
    public $confirmingJobDeletion = false;

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
        $this->pathName = request()->route()->getName();;
        $categories = Category::all();
        foreach ($categories as $category)
        {
            $this->categories[$category->id] =  $category->name;
        }
        $user = auth()->user();
        //dd($user);
        $this->userID = $user->id; 
        $this->permission = $user->role == "admin" ? 1 : 0;
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
        //dd($this->userID);
        $jobs = [];
        if(isset($this->job) && $this->jobID !== -1)
        {
            $data = Job::find($this->jobID)->toArray();
            $configuration = json_decode($data['configuration']);
            $this->outputFileJson = json_decode( $configuration->output_file_json, true);
            $this->inputFileJson = json_decode( $configuration->input_file_json, true); 
            (count($this->outputFileJson['fileName']) >= 1  &&
            count($this->uploadFields) == count($this->inputFileJson['fileName']))?
            $this->displayEditable = true : $this->displayEditable = false;  

        }
        
        else
        {            
            $jobs = Job::leftjoin('users', 'jobs.user','=','users.id')->where('jobs.name', 'like', '%'.$this->nameSearch.'%') ->orWhere('users.name', 'like', '%'.$this->nameSearch.'%');         
            if(!$this->permission){
                $jobs = $jobs->where('users.id', $this->userID);
        }
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
                $this->job->input_file_json = json_encode($this->inputFileJson);
                
                
            } 

            catch (\Exception $e) 
            {
                session()->flash('flash.banner', 'Something Wrong while Updating Job');
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);;
            }
            $data = job::find($this->job->id)->toArray();
            $data['configuration'] = json_decode($data['configuration'],true);
            $data['configuration']['input_file_json'] = $this->job->input_file_json;
            unset($this->job->input_file_json);

            if(!empty($this->job->output_file_json)){
            //  $data['configuration'] = json_decode($data['configuration'],true);
            $data['configuration']['output_file_json'] = $this->job->output_file_json;
            unset($this->job->output_file_json);
            }
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
                return redirect()->route(self::REDIRECT_ROUTE);
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
                $configuration = json_decode($this->job->configuration);
              //  $this->jobAttr['configuration'] =  $this->configuration;
                $this->uploadFields = json_decode($configuration->input_property_json,true);
              //  $this->uploadFields = json_decode($this->job->input_property_json,true);

                foreach ($this->uploadFields as $fileType => $extension){
                    $this->inputFiles[$fileType] = null;
                }       
            } 
        }
      
    }

    
     /**
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
        return redirect()->route($this->pathName , ['jobID' => $jobID]);
    }

}
