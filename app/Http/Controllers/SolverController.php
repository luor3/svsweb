<?php

namespace App\Http\Controllers;

use App\Models\solver;
use Illuminate\Http\Request;

class SolverController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'solver.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'solver.create';
    
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['solver.*'];
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['solver' => solver::all()]
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
