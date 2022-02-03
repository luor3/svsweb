<?php

namespace App\Http\Livewire\Sshservers;

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
    public $server_name = '';

    /**
     * 
     * @var string
     */
    public $host = '';
    /**
     * 
     * @var string
     */
    public $port = '';
    /**
     * 
     * @var string
     */
    public $username = '';
    /**
     * 
     * @var string
     */
    public $password = '';

     /**
     * 
     * @var string
     */
    public $sid;
    
    /**
     * 
     * @var sshserver
     */
    public $sshserver;

    
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
        if (isset($this->sshserver)) {
            $this->server_name = $this->sshserver->server_name;
            $this->host = $this->sshserver->host;
            $this->port = $this->sshserver->port;
            $this->username = $this->sshserver->username;
            $this->password = $this->sshserver->password;
            $this->sid = $this->sshserver->id;

        }
        
        return view('sshservers.update-form');
    }
    
    /**
     * add new setting
     */
    public function update()
    {   
        $validatedData = $this->validate();
        if ($this->sshserver) {
            $this->sshserver->server_name = $this->server_name;
            $this->sshserver->host = $this->host;
            $this->sshserver->port = $this->port;
            $this->sshserver->username = $this->username;
            $this->sshserver->password = $this->password;
            $status = $this->sshserver->save();
            
            $this->emit('saved', $status);
        }
    }
    
    public function delete()
    {
        if ($this->sshserver && $this->sshserver->id) {
            $deleted = $this->sshserver->delete();
            
            $msg =  $deleted ? 'Setting successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($deleted) {    
                return redirect()->route('sshservers');
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
        ];
    }
}

