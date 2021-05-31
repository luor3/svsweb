<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\App;

class DynamicPagesController extends Controller
{
    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [];
    
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'dynamic';
    
    /**
     * Show page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = $this->getData($request);
        
        // if not found, return 404 page
        if (empty($data['page'])) {
            abort(404);
        }
        
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
