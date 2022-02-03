<?php

namespace App\Http\Livewire\Sshservers;

use Livewire\Component;
use App\Models\Sshservers;

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
        'server_name' => 'required|unique:sshservers|max:255',
        'host' => 'required',
        'port' => 'nullable|string',
        'username' => 'required',
        'password' => 'required',

    ];
    
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
     * @var sshserver
     */
    protected $sshserver;

    /**
     * 
     * @return view
     */
    public function render()
    {
        $this->user = auth()->user();
        
        return view('sshservers.create-form');
    }
    
    /**
     * add new setting
     */
    public function add()
    {   
        $data = $this->validate();
        $status = Sshservers::create($data);
       
        $msg =  $status ? 'Setting successfully added!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';
        $redirect = $status ? 'sshservers' : 'sshservers.create';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route($redirect);
        }
    }
}
