<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmitController extends Controller
{
    
    /**
     * submit page
     *
     * @return json
     */
    public function submit(Request $request, $formType)
    {
        if($formType == 'contact'){
            $success = $this->insertContactInfo($request);
            if($success){
                return response()->json([
                    'submitType' => $formType,
                    'success' => true
                ]);
            }
            else{
                return response()->json([
                    'submitType' => $formType,
                    'success' => false
                ]);
            }
        }
        abort(404);        
    }
    
    /**
     * 
     * @param Request $request
     * @return boolean
     */
    private function insertContactInfo(Request &$request) 
    {
        $input = $request->all();  
        $success = false;
        if($input['email'] != '' && filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
            if($input['name']!='' && $input['message']!=''){
                try {
                    $success = DB::table('contact_information')->insert([
                        'email' => $input['email'],
                        'name' => $input['name'],
                        'content' => $input['message'],
                        'ip-addr' => $request->ip(),
                        'added_at' => date('Y-m-d H:i:s')
                    ]);
                } catch (Exception $e) {
                    $success = false;
                }               
            }
        }          
        return $success;
    }
}
