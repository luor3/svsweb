<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\App;
use App\Models\Demo;

class GetstartedController extends Controller
{

    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title'         => "Demo",
        'description'   => "SVS-EFIE Solver's Get Started",
        'keywords'      => "input, svs, svs-efie, generator, input generator",
    ];
    
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'frontdemo';


    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $this->getData($request);
        
        return view(
            self::TEMPLATE_FILE, 
            $data,
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