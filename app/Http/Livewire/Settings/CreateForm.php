<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\Settings;

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
        'name' => 'required|unique:settings|max:255',
        'value' => 'required',
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
    public $value = '';

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        
        return view('settings.create-form');
    }
    
    /**
     * add new setting
     */
    public function add()
    {
        $data = $this->validate();
        
        $status = Settings::create($data);

        /* $setting = new Settings;
        $setting->name = $this->name;
        $setting->value = $this->value;
        $status = $setting->save(); */
        
       
        $msg =  $status ? 'Setting successfully added!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';
        $redirect = $status ? 'settings' : 'settings.create';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route($redirect);
        }
    }
}
