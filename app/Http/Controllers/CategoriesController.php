<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoriesController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'categories.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'categories.create';
    
     
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['categories' => Category::all()]
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
