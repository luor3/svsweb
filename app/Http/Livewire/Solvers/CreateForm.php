<?php

namespace App\Http\Livewire\Solvers;

use Livewire\Component;
use App\Models\solvers;

class CreateForm extends Component
{
    /**
     * 
     * @var auth()->user()
     */
    public $user;
    
    
    /**
     * 
     * @var type
     */
    protected $rules = [
        'name' => 'required|unique:solvers|max:255',
        'args' => 'required',
    ];
    
    /**
     * 
     * @var string
     */
    public $name = '';

     /**
     * 
     * @var string
     */
    public $description = '';

    /**
     * 
     * @var string
     */
    public $args = '';
    
    /**
     * 
     * @var solver
     */
    protected $solver;

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        
        return view('solvers.create-form');
    }
    
    /**
     * add new setting
     */
    public function add()
    {   
        $data = $this->validate();

        $status = solvers::create($data);
       
        $msg =  $status ? 'Setting successfully added!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';
        $redirect = $status ? 'solvers' : 'solvers.create';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route($redirect);
        }
    }
}
