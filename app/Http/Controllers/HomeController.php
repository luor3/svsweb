<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'home';
    
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view(
            self::TEMPLATE_FILE, 
            $this->getData()
        );
    }
    
    /**
     * 
     * @return array
     */
    private function getData()
    {
        return ['terms' => 'testing'];
    }
}
