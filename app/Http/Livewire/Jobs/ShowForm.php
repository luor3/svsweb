<?php

namespace App\Http\Livewire\Jobs;

use Livewire\Component;
use App\Models\Job;
use App\Models\User;
use Livewire\WithPagination;


class ShowForm extends Component
{
    
    use WithPagination;
   
    public $job;
    
    public $status;

    public $jobID = -1;

    public $userID;

    public $pageNum = 5;

    public $configuration; 

    public $permission;
    
    public $confirmingJobDeletion = false;
    
    public $jobAttr = [
        'user' => null,
        'configuration' => null,
        'status' => null,
        'category_id' => null,
    ];
    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'users.update-form';
    
    const REDIRECT_ROUTE = 'jobs';

    /**
     * reset page number before updating pageNum
     * 
     * @return void
     */
    public function updatingPageNum()
    {
        $this->resetPage();
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
     * register Jobs for further operation (update and delete)
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
                $this->jobAttr['user'] = $this->job->user;aozuo;
                $this->jobAttr['configuration'] = $this->job->configuration;
                $this->jobAttr['category_id'] = $this->job->category_id;
                $this->jobAttr['status'] = $this->job->status;

                $this->uploadFields = json_decode($this->job->input_property_json,true);
                foreach ($this->uploadFields as $fileType => $extension){
                    $this->inputFiles[$fileType] = null;
                }       
            } 
        }
    }

    public function render()
    {
        
        $jobs = Job::leftjoin('users', 'jobs.user','=','users.id');
        if(!$this->permission){
            $jobs = $jobs->where('users.id', $this->userID);
        }
        $jobs = $jobs -> paginate($this->pageNum,['jobs.*','users.name AS user_name']);

        return view('jobs.show-form',['jobs' => $jobs]);
    }


    public function mount()
    {
        $user = auth()->user();
        $this->userID = $user->id; 
        $this->permission = $user->role == "admin" ? 1 : 0;
        if($this->jobID !== -1)
        {
            $this->registerJob($this->jobID, false);
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
     * call after clicking "Edit" in job page
     * 
     * @return redrect route 
     */
    public function redirecToJob($jobID)
    {
        return redirect()->route('jobs',['jobID' => $jobID]);
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
            $this->job->name = $this->jobAttr['user'];
            $this->job->configuration = $this->jobAttr['configuration'];
            $this->job->category_id = $this->jobAttr['category_id'];
            $this->job->status = $this->jobAttr['status'];

                session()->flash('flash.banner', 'Something Wrong while Updating Demo');
                session()->flash('flash.bannerStyle', 'danger');
               
            
                           
            $status = $this->job->save();           
            $this->emit('saved', $status);
        }
    }
}
