<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class UserProfile extends Component
{

    public $currentModule = "userprofile";


    /**
     * 
     * @var array
     */
    protected $queryString = [
        'currentModule' => ['except' => 'userprofile'],
    ];

    public function render()
    {
        return view('frontend.user-profile');
    }

    public function setPath($route)
    {
        $this->currentModule = $route;
    }
}
