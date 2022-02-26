<?php

namespace App\Http\Livewire\Solvers;

use Livewire\Component;

class UpdateForm extends Component
{
 
    
    /**
     * 
     * @var integer
     */
    public $cid;
    
    /**
     * 
     * @var string
     */
    public $name;

    public $description = '';


    /**
     * 
     * @var string
     */
    public $args;
    
    /**
     * 
     * @var solver
     */
    public $solver;
    
    /**
     * 
     * @var boolean
     */
    public $confirmingSettingDeletion = false;
    

    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|max:255|unique:solvers,name,'.$this->cid,
            'args' => 'required',
        ];
    }



    public function render()
    {
        if (isset($this->solver)) {
            $this->name = $this->solver->name;
            $this->args = $this->solver->args;
        }

        return view('solvers.update-form');
    }


    public function update()
    {
        $this->validate();
        
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
                return redirect()->route('categories');
            }
        }
    }
}
