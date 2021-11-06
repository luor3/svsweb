<?php

namespace App\Http\Livewire\Jobs;

use Livewire\Component;
use App\Models\Job;
use App\Models\User;
use Livewire\WithPagination;


class ShowForm extends Component
{
    
    use WithPagination;
    
    public $status;

    public $jobID;

    public $userID;

    public $pageNum = 5;

    public $configuration; 

    public $permission;

    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'users.update-form';


    /**
     * reset page number before updating pageNum
     * 
     * @return void
     */
    public function updatingPageNum()
    {
        $this->resetPage();
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
    }


}
