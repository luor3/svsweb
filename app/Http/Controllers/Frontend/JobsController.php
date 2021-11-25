<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

use App\Models\Page;
use App\Models\App;

class JobsController extends Controller
{
    
    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title'         => "Users Profile",
        'description'   => "SVS-EFIE Solver's Users Profile",
        'keywords'      => "profile, svs, svs-efie, generator, user profile",
    ];
    
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'frontjobshow';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'frontjobcreate';
    
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['jobs.*'];
    
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function show(Request $request)
    {
        $data = $this->getData($request);
        return view(
            self::PAGE_TEMPLATE, 
            $data
        );
    }
    
    /**
     * 
     * @param Request $request
     * @return JSON
     */
    public function showCreate(Request $request)
    {
        $data = $this->getData($request);
        return view(
            self::CREATE_TEMPLATE, 
            $data
        );
    }

    public function deletes(){
       $id=$_GET['id'];
       $isok=DB::table('jobs')->delete($id);
     if($isok){
            echo "<script>alert('success');location.href='/admin/jobs'</script>";
     }
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
