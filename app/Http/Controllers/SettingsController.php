<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'settings.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'settings.create';
    
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['settings.*'];
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['settings' => Settings::all()]
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
