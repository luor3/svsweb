<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Selected DB columns
     *
     * @var type 
     */
    private static $fields = [ 'pages.*' ];
    
    /**
     * Fetch records
     * 
     * @param Request $request
     * @return json|array
     */
    public function fetch(Request $request)
    {
        $recordPerPage = $request->get('perPage');
        $offset = $request->get('offset');
        $search = trim($request->get('search'));
        $orderBy = $request->get('orderBy');
        $orderType = $request->get('orderType');
        
        $items = Page::where([['pages.id', '>', 0], ['pages.link', 'LIKE', '%'.$search.'%']])
                ->orWhere(function ($query) use ($search) {
                    $query->where('pages.title', 'LIKE', '%'.$search.'%')
                        ->orWhere('pages.description', 'LIKE', $search.'%')
                        ->orWhere('pages.keywords', 'LIKE', $search.'%')
                        ->orWhere('pages.content', 'LIKE', $search.'%')
                        ->orWhere('pages.created_at', 'LIKE', $search.'%')
                        ->orWhere('pages.updated_at', 'LIKE', $search.'%');
                })
                ->orderBy("pages.$orderBy", "$orderType");
        
        $totalItems = empty($search) ? Page::where([['id', '>', 0]]) : $items;
        $total = count($totalItems->get());
        $rows = $items->skip($offset)->take($recordPerPage)->get(self::$fields);
        
       $data = [
            'status'=>1, 
            'msg' => 'Pages successfully loaded.', 
            'total' => $total, 
            'page' => $rows
        ];
        
        if ($request->ajax()  || $request->wantsJson()) {
            return response()->json($data);
        }
        
        return $data;
    }
    
    /**
     * Fetch all records
     * 
     * @return json
     */
    public function fetchAll()
    {
        return Page::all();
    }

    /**
     * Fetch row by ID
     * 
     * @param integer $id
     * @return json
     */
    public function fetchById($id)
    {
        $item = Page::where([['pages.id', '=', $id]])->get(self::$fields)->first();
        return response()->json($item);
    }
    
    /**
     * Delete row
     * 
     * @param Request $request
     * @param integer $id
     * @return json
     */
    public function delete(Request $request, $id)
    {
        $response = Page::destroy($id);
        $message = [
            'status' => !empty($response) ? 1 : 0, 
            'msg' => !empty($response) ? 'Page successfully deleted.' : 'Page cannot be deleted!', 
            'response' => $response
        ];

        if($request->ajax() || $request->wantsJson()){
            return response()->json($message);
        }
        
        return redirect()->route('admin-pages')->with('message', $message);
    }
    
    /**
     * Change row status
     * 
     * @param Request $request
     * @param integer $id
     * @return json
     */
    public function toggleStatus(Request $request, $id)
    {
        $row = Page::where([['pages.id', '=', $id]])->get(self::$fields)->first();
        $row->status = ($row->status == 1) ? 0 : 1;
        $status = $row->save();
        
        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status' => !empty($status) ? 1 : 0, 
                'msg' => !empty($status) ? 'Page successfully updated.' : 'Page status change failed!', 
                'page' => $row
            ]);
        }
        
        return redirect()->route('admin-pages')->with('message', "Page status successfully changed!");
    }
    
    /**
     * 
     * @param PageRequest $request
     * @return json
     */
    public function add(PageRequest $request)
    {
        $row = new Page;
        $row->link = $request->input('link');
        $row->title = $request->input('title');
        $row->description = $request->input('description');
        $row->keywords = $request->input('keywords');
        $row->content = $request->input('content');
        $row->status = $request->input('status');
        $status = $row->save();
        
        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status' => !empty($status) ? 1 : 0, 
                'msg' => 'Page successfully added.', 
                'page' => $row
            ]);
        }
        
        return redirect()->route('admin-pages')->with('message', "Page #$row->id successfully added!");
    }
    
    /**
     * 
     * @param PageRequest $request
     * @param int $id
     * @return json
     */
    public function update(PageRequest $request, $id)
    {
        $row = Page::find($id);
        $row->link = $request->input('link');
        $row->title = $request->input('title');
        $row->description = $request->input('description');
        $row->keywords = $request->input('keywords');
        $row->content = $request->input('content');
        $row->status = $request->input('status');
        $done = $row->save();
        
        if($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => !empty($done) ? 1 : 0, 
                'msg' => !empty($done) ? 'Page successfully updated.' : 'Ooops something went wrong!', 
                'page' => $row
            ]);
        }
    }
}