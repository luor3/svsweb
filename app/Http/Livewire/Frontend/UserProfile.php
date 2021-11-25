<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class UserProfile extends Component
{

    public $user;

    public $name;

    public $email;


    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|max:255',
            'email'=> 'required|email|unique:users,email,'. $this->user->id,
        ];
    }

    public function mount(){
        $this->user = auth()->user();
    }


    public function render()
    {
        
        if (isset($this->user)) {
            $this->name = $this->user->name;
            $this->email = $this->user->email;
        }

        return view('frontend.user-profile');
    }

    /**
     * update user
     * 
     * @return void
     */
    public function update()
    {
        $this->validate();

        if ($this->user) {
            $this->user->name = $this->name;
            $this->user->email = $this->email;

            $status = $this->user->save();
            
            $this->emit('saved', $status);
        }
    }
}
