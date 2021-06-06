<?php

namespace App\Http\Livewire\Settings;

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
     * @var integer
     */
    public $sid;
    
    /**
     * 
     * @var string
     */
    public $name;

    /**
     * 
     * @var string
     */
    public $value;
    
    /**
     * 
     * @var Settings
     */
    public $setting;
    
    /**
     * 
     * @var boolean
     */
    public $confirmingSettingDeletion = false;

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        
        if (isset($this->setting)) {
            $this->name = $this->setting->name;
            $this->value = $this->setting->value;
            $this->sid = $this->setting->id;
        }
        
        return view('settings.update-form');
    }
    
    /**
     * add new setting
     */
    public function update()
    {
        $this->validate();
        
        if ($this->setting) {
            $this->setting->name = $this->name;
            $this->setting->value = $this->value;
            $status = $this->setting->save();
            
            $this->emit('saved', $status);
        }
    }
    
    public function delete()
    {
        if ($this->setting && $this->setting->id) {
            $deleted = $this->setting->delete();
            
            $msg =  $deleted ? 'Setting successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($deleted) {    
                return redirect()->route('settings');
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
            'name' => 'required|max:255|unique:settings,name,'.$this->sid,
            'value' => 'required',
        ];
    }
}

