<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demo;

class DemosController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'demos.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'demos.create';
    
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['demos.*'];
    
    
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
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function showCreate(Request $request)
    {
        return view(
            self::CREATE_TEMPLATE, 
            []
        );
    }
}
