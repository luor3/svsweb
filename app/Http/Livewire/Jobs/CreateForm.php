<?php

namespace App\Http\Livewire\Jobs;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Job;

class CreateForm extends Component
{
    /**
     * 
     * @var string
     */
    public $user;
    
    /**
     * 
     * @var string
     */
    public $configuration;

    /**
     * 
     * @var boolean
     */
    public $status;
    
    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'jobs.create-form';
    
    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'jobs';

    /**
     * 
     * @return view
     */
    public function render()
    {
        return view(self::COMPONENT_TEMPLATE);
    }
    
    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'configuration' => 'min:0',
            'status' => 'required',
        ];
    }
    
    /**
     * add new page
     * 
     * @return view Pages section
     */
    public function create(Request $request)
    {
        $data = $this->validate();
        $data['user'] = auth()->user()->id;        
        $status = Job::create($data);

        $msg =  $status ? 'Job successfully submitted!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route(self::REDIRECT_ROUTE);
        }
    }
}
