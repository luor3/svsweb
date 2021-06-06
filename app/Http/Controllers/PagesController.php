<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'pages.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'pages.create';
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show()
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['pages' => Page::all()]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function showCreate()
    {
        return view(
            self::CREATE_TEMPLATE, 
            []
        );
    }
}