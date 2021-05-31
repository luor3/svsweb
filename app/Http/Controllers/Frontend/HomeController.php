<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\App;

class HomeController extends Controller
{
    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title'         => "SVS-EFIE Solver",
        'description'   => "Default description's",
        'keywords'      => "electromagnetic, svs, svs-efie"
    ];
    
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'home';
    
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view(
            self::TEMPLATE_FILE, 
            $this->getData($request)
        );
    }
    
    /**
     * 
     * @return array
     */
    private function getData(Request $request)
    {
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        
        return [
            'app'               => new App(),
            'page'              => $page
        ];
    }
}
