<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = ['settings.*'];
    
     /**
     * 
     * @param Request $request
     * @return JSON|View
     */
    public function fetch(Request $request)
    {
        $recordPerPage = $request->get('perPage');
        $offset = $request->get('offset');
        $search = trim($request->get('search'));
        $orderBy = $request->get('orderBy');
        $orderType = $request->get('orderType');
        
        $items = Settings::where([['settings.id', '>', 0], ['settings.name', 'LIKE', '%'.$search.'%']])
                ->orWhere(function ($query) use ($search) {
                    $query->where('settings.value', 'LIKE', '%'.$search.'%')
                        ->where('settings.created_at', 'LIKE', '%'.$search.'%')
                        ->orWhere('settings.updated_at', 'LIKE', '%'.$search.'%');
                })
                ->orderBy("settings.$orderBy", "$orderType");
        
        $totalItems = empty($search) ? Settings::where([['id', '>', 0]]) : $items;
        $total = count($totalItems->get());
        $rows = $items->skip($offset)->take($recordPerPage)->get(self::$fields);
        
        return response()->json([
            'status'=>1, 
            'msg' => 'Settings successfully loaded.', 
            'total' => $total, 
            'setting' => $rows
        ]);
    }
    
    /**
     * 
     * @return JSON
     */
    public function fetchAll()
    {
        return Settings::all();
    }

    /**
     * 
     * @param int $id
     * @return JSON
     */
    public function fetchById($id)
    {
        return response()->json(Settings::find($id));
    }

    /**
     * 
     * @param SettingsRequest $request
     * @return JSON|View
     */
    public function add(SettingsRequest $request)
    {
        $setting = new Settings;
        $setting->name = $request->input('name');
        $setting->value = $request->input('value');
        $status = $setting->save();
        
        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'=> !empty($status) ? 1 : 0, 
                'msg' => !empty($status) ? 'Setting successfully added.' : 'Setting creation failed!', 
                'setting' => $setting
            ]);
        }
        return redirect()->route('admin-settings')->with('message', "$setting->name setting successfully added!");
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @return JSON|View
     */
    public function delete(Request $request, $id)
    {
        $response = Settings::destroy($id);

        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'=> !empty($response) ? 1 : 0, 
                'msg' => !empty($response) ? 'Setting successfully deleted.' : 'Setting cannot be deleted!', 
                'response' => $response
            ]);
        }
        return redirect()->route('admin-settings')->with('message', "Setting successfully deleted!");
    }
    
    /**
     * 
     * @param SettingsRequest $request
     * @return JSON|View
     */
    public function update (SettingsRequest $request, $id = null) 
    {
        $find = !empty($id) ? $id : $request->input('id');
        $setting = Settings::find($find);
        $setting->name = $request->input('name');
        $setting->value = $request->input('value');//($request->input('value') == 'True') ? 1 : 0;
        $response = $setting->save();
        
        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'=> !empty($response) ? 1 : 0, 
                'msg' => !empty($response) ? 'Setting successfully updated.' : 'Setting cannot be updated!', 
                'setting' => $setting
            ]);
        }
        return redirect()->route('admin-settings')->with('message', "$setting->name setting successfully updated!");
    }

}
