<?php

namespace App\Http\Controllers;

use App\Models\solvers;
use Illuminate\Http\Request;

class SolverController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'solvers.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'solvers.create';
    

    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['solvers' => solvers::all()]
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
