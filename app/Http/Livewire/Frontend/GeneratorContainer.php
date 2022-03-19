<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class GeneratorContainer extends Component
{


    public $currentModule = "input";


    /**
     * 
     * @var array
     */
    protected $queryString = [
        'currentModule' => ['except' => 'input'],
    ];

    public function setPath($route)
    {
        $this->currentModule = $route;
    }


    public function render()
    {
        return view('frontend.generator-container');
    }
}
