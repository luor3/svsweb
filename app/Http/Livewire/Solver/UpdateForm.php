<?php

namespace App\Http\Livewire\Solver;

use Livewire\Component;

class UpdateForm extends Component
{
    /**
     * 
     * @var auth()->user()
     */
    public $user;
    
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
    
    public $solver;

    
    /**
     * 
     * @var boolean
     */
    public $confirmingSshserverDeletion = false;

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        if (isset($this->solver)) {
            $this->name = $this->solver->name;
            $this->args = $this->solver->args;
        }
        
        return view('solver.update-form');
    }
    
    /**
     * add new setting
     */
    public function update()
    {   
        $validatedData = $this->validate();
        if ($this->solver) {
            $this->solver->name = $this->name;
            $this->solver->args = $this->args;
            $status = $this->solver->save();
            
            $this->emit('saved', $status);
        }
    }
    
    public function delete()
    {
        if ($this->solver && $this->solver->id) {
            $deleted = $this->solver->delete();
            
            $msg =  $deleted ? 'Setting successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($deleted) {    
                return redirect()->route('solver');
            }
        }
    }
    
    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'server_name' => 'required|max:255|unique:sshservers,server_name,'.$this->sid,
            'host' => 'required',
            'port' => 'nullable|string',
            'username' => 'required',
            'password' => 'required',
            'active' => 'nullable|boolean',
        ];
    }
}

