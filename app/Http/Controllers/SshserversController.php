<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sshservers;

class SshserversController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'sshservers.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'sshservers.create';
    
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['sshservers.*'];
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        return view(
            self::PAGE_TEMPLATE, 
            ['sshservers' => Sshservers::all()]
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
