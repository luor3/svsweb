<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\App;
use App\Models\Job;

class VtkController extends Controller
{

    public $vtk_path;

   /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title'         => "VTK visualizer",
        'description'   => "SVS-EFIE Solver's VTK Visualizer",
        'keywords'      => "input, svs, svs-efie, generator, input generator",
    ];

    
    /**
     * @var string
     */
    const TEMPLATE_FILE = 'frontendvtkvisualizer';



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
        if($request->has('vtkPath')) 
        {
            $this->vtk_path = $request->vtkPath;
        }
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        
        return [
            'app'           => new App(),
            'page'          => $page,
            'vtk_path'      => $this->vtk_path
        ];
    }
}
