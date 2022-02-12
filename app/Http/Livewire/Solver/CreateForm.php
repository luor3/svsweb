<?php

namespace App\Http\Livewire\Solver;

use Livewire\Component;
use App\Models\solver;

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
        'name' => 'required|unique:solver|max:255',
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
    public $args = '';
    
    /**
     * 
     * @var sshserver
     */
    protected $solver;

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        
        return view('solver.create-form');
    }
    
    /**
     * add new setting
     */
    public function add()
    {   
        $data = $this->validate();
        $status = solver::create($data);
       
        $msg =  $status ? 'Setting successfully added!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';
        $redirect = $status ? 'solver' : 'solver.create';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route($redirect);
        }
    }
}
