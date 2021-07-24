<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class CreateForm extends Component
{
 
    /**
     * 
     * @var type
     */
    protected $rules = [
        'name' => 'required|unique:categories|max:255',
        'description' => 'required',
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
     * @return view
     */
    public function render()
    {    
        return view('categories.create-form');
    }
    
    /**
     * add new setting
     */
    public function add()
    {
        $data = $this->validate();
        
        $status = Category::create($data);
        
       
        $msg =  $status ? 'Category successfully added!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';
        $redirect = $status ? 'categories' : 'categories.create';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route($redirect);
        }
    }
}
