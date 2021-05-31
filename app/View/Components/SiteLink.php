<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SiteLink extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view(
            'components.site-link', 
            [
                //'active' => request()->routeIs('homepage')
            ]
        );
    }
}
