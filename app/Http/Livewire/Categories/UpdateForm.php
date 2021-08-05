<?php

namespace App\Http\Livewire\Categories;

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

    /**
     * 
     * @var string
     */
    public $description;
    
    /**
     * 
     * @var Category
     */
    public $category;
    
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
            'name' => 'required|max:255|unique:categories,name,'.$this->cid,
            'description' => 'required',
        ];
    }



    public function render()
    {
        if (isset($this->category)) {
            $this->name = $this->category->name;
            $this->description = $this->category->description;
            $this->cid = $this->category->id;
        }

        return view('categories.update-form');
    }


    public function update()
    {
        $this->validate();
        
        if ($this->category) {
            $this->category->name = $this->name;
            $this->category->description = $this->description;
            $status = $this->category->save();
            
            $this->emit('saved', $status);
        }
    }
    
    public function delete()
    {
        if ($this->category && $this->category->id) {
            $deleted = $this->category->delete();
            
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
