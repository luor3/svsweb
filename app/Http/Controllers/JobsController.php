<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Job;

class JobsController extends Controller
{
    /**
     * @var string
     */
    const PAGE_TEMPLATE = 'jobs.show';
    
    /**
     * @var string
     */
    const CREATE_TEMPLATE = 'jobs.create';
    
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
        return view(
            self::PAGE_TEMPLATE, 
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
    public function deletes(){
       $id=$_GET['id'];
       $isok=DB::table('jobs')->delete($id);
     if($isok){
            echo "<script>alert('success');location.href='/admin/jobs'</script>";
     }
    }
}
