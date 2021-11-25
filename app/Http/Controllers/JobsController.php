<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'jobs.show';
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
        );
    }
}
