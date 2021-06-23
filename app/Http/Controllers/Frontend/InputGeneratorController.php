<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\App;

class InputGeneratorController extends Controller
{
    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title'         => "Input Generator: SVS-EFIE Solver",
        'description'   => "SVS-EFIE Solver's Input Generating Feature",
        'keywords'      => "input, svs, svs-efie, generator, input generator",
        'content'       => '',
    ];
    
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'input-generator';
    
    /**
     * Show page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = $this->getData($request);
        
        return view(
            self::TEMPLATE_FILE, 
            $data
        );
    }
    
    /**
     * 
     * @param Request $request
     * @return array
     */
    private function getData(Request &$request) 
    {
        
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        
        return [
            'app'           => new App(),
            'page'          => $page
        ];
    }
}