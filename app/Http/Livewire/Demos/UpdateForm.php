<?php

namespace App\Http\Livewire\Demos;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Demo;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use Livewire\WithPagination;

class UpdateForm extends Component
{

    use WithFileUploads;

    use WithPagination;

    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'demos.update-form';
    

    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'demos';
    

    /**
     * 
     * @var Demo
     */
    public $demo;


    /**
     * 
     * @var Demo id
     */
    public $demoID = -1;

    /**
     * 
     * @var Category array
     */
    public $categories = []; 


    /**
     * 
     * @var File array
     */
    public $outputFiles;


    /**
     * 
     * @var File array
     */
    public $inputFiles;



    public $uploadFields = [];

    /**
     * 
     * @var File
     */
    public $plotFile;


    /**
     * 
     * @var boolean
     */
    public $confirmingDemoDeletion = false;


    /**
     * 
     * @var boolean
     */
    public $displayEditable = false;



    /**
     * 
     * @var array
     */
    public $outputFileJson = [];



    /**
     * 
     * @var array
     */
    public $inputFileJson = [];



    /**
     * 
     * @var array
     */
    public $demoAttr = [
        'name' => null,
        'description' => null,
        'status' => null,
        'category_id' => null,
    ];


    public $currentOrderProperty;


    public $currentOrder = 'asc';

    
    /**
     * 
     * @var integer
     */
    public $categorySearch = -1;


    /**
     * 
     * @var integer
     */
    public $pageNum = 5;


    /**
     * 
     * @var string
     */
    public $nameSearch = '';



    /**
     * 
     * @var array
     */
    protected $queryString = [
        'categorySearch' => ['except' => -1],
        'pageNum',
        'nameSearch' => ['except' => ''],
        'demoID' => ['except' => -1]
    ];



    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'demoAttr.name' => 'required|max:255|unique:demos,name,'.$this->demo->id,
            'demoAttr.category_id' => 'required|integer',
            'demoAttr.description' => 'required|max:1024',
            'demoAttr.status' => 'required|boolean',
            'outputFiles.*' => 'file',       
        ];
    }


    /**
     * reset page number before updating category search
     * 
     * @return void
     */
    public function updatingCategorySearch()
    {
        $this->resetPage();
    }

    /**
     * reset page number before updating name search
     * 
     * @return void
     */
    public function updatingNameSearch()
    {
        $this->resetPage();
    }

    /**
     * reset page number before updating pageNum
     * 
     * @return void
     */
    public function updatingpageNum()
    {
        $this->resetPage();
    }


    /**
     * initialize the demo page properties
     * @return void
     */
    public function mount()
    {
        $categories = Category::all();
        foreach ($categories as $category)
        {
            $this->categories[$category->id] =  $category->name;
        }
        if($this->demoID !== -1)
        {
            $this->registerDemo($this->demoID, false);
        }
            
    }


    /**
     * render the demo
     * @return View Demo section
     */
    public function render()
    {  
        $demos = [];
        if(isset($this->demo) && $this->demoID !== -1)
        {

            $this->outputFileJson = json_decode($this->demo->output_file_json, true);
            $this->inputFileJson = json_decode($this->demo->input_file_json, true); 

            (count($this->outputFileJson['fileName']) >= 1  &&
            count($this->uploadFields) == count($this->inputFileJson['fileName']))?
            $this->displayEditable = true : $this->displayEditable = false;          
        }
        
        else
        {
            $demos = Demo::leftjoin('categories', 'demos.category_id','=','categories.id')->where('demos.name', 'like', '%'.$this->nameSearch.'%');

            if($this->categorySearch != -1)
            {
                $demos = $demos->Where('category_id', $this->categorySearch);
            }

            if(!is_null($this->currentOrderProperty) && $this->currentOrder !== '')
            {
                $demos = $demos -> orderBy($this->currentOrderProperty,$this->currentOrder);  
            }
            

            $demos = $demos->paginate($this->pageNum,['demos.*','categories.name AS category_name']);
        }
        

        return view(self::COMPONENT_TEMPLATE,['demos' => $demos]);
    }


    /**
     * update demo
     * 
     * @return void
     */
    public function update()
    {
        if(!auth()->user()->hasPermission(2)){
            session()->flash('flash.banner', "You do not have permission to do that!");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::REDIRECT_ROUTE);
        }

        $this->validate();
    
        if (isset($this->demo)) 
        { 
            $this->demo->name = $this->demoAttr['name'];
            $this->demo->description = $this->demoAttr['description'];
            $this->demo->category_id = $this->demoAttr['category_id'];
            $this->demo->status = $this->demoAttr['status'];

            try 
            {
                if(isset($this->plotFile))
                {
                    if($this->demo->plot_path)
                    {
                        Storage::disk('public')->delete($this->demo->plot_path);
                    }                
                    $this->demo->plot_path = $this->plotFile->store('demos/'.$this->demo->id,'public');

                    $this->plotFile = null;
                }           
                
                if(isset($this->outputFiles))
                {
                    
                    foreach ($this->outputFileJson['fileName'] as $fileName)
                    {
                        Storage::disk('public')->delete($this->outputFileJson[$fileName]);
                    } 

                    $output_json = [
                        'fileName' => [],
                    ];
                    foreach ($this->outputFiles as $file)
                    {
                        
                        $uniqueFileName = Str::uuid().$file->getClientOriginalName();
                        array_push($output_json['fileName'],$uniqueFileName);
                        $output_json[$uniqueFileName] = $file->store('demos/'.$this->demo->id,'public');
                    }
                    $this->demo->output_file_json  = json_encode($output_json);
                    $this->outputFiles = null;
                }
                
                foreach ($this->inputFiles as $fileType => $file)
                {
                    if(isset($this->inputFiles[$fileType]))
                    {
                        if(isset($input_file_json['fileName'][$fileType]))
                        {
                            Storage::disk('public')->delete($this->inputFileJson[$fileType]);
                        }       
                        $this->inputFileJson['fileName'][$fileType] = $file->getClientOriginalName();
                        $this->inputFileJson[$fileType] = $file->store('demos/'.$this->demo->id,'public');
                    }
                }
                $this->demo->input_file_json = json_encode($this->inputFileJson);
                
                
            } 
            catch (\Exception $e) 
            {
                session()->flash('flash.banner', 'Something Wrong while Updating Demo');
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);;
            }
                           
            $status = $this->demo->save();           
            $this->emit('saved', $status);
        }
    }


    /**
     * delete Demo
     * 
     * @return view
     */
    public function delete()
    {
        if(!auth()->user()->hasPermission(3)){
            session()->flash('flash.banner', "You do not have permission to do that!");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::REDIRECT_ROUTE);
        }

        if ($this->demo && $this->demo->id) 
        {
  
            $deleted = $this->demo->delete(); // this will also delete related files through ORM deleting hook function.
            $msg =  $deleted ? 'Demo successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
                                                  
            if ($deleted) 
            {    
                return redirect()->route(self::REDIRECT_ROUTE);
            }
        }
    }


    /**
     * register Demo for further operation (update and delete)
     * 
     * @return view
     */
    public function registerDemo($demo_id, $delete)
    {
        $this->demo = Demo::find($demo_id);
        if(is_null($this->demo))
        {
            abort(404);
        }
        if($delete)
        {
            if(!auth()->user()->hasPermission(3)){
                session()->flash('flash.banner', "You do not have permission to delete demo!");
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);
            }

            $this->confirmingDemoDeletion = true;
        }
        else
        {
            if(!auth()->user()->hasPermission(2)){
                session()->flash('flash.banner', "You do not have permission to edit demo!");
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);
            }

            $this->demoID = $this->demo->id;
            if (isset($this->demo)) 
            {
                $this->demoAttr['name'] = $this->demo->name;
                $this->demoAttr['description'] = $this->demo->description;
                $this->demoAttr['category_id'] = $this->demo->category_id;
                $this->demoAttr['status'] = $this->demo->status;

                $this->uploadFields = json_decode($this->demo->input_property_json,true);
                foreach ($this->uploadFields as $fileType => $extension){
                    $this->inputFiles[$fileType] = null;
                }       
            } 
        }
    }


    /**
     * call after clicking "back" in demo edit page
     * 
     * @return void
     */
    public function clearDemo()
    {
        $this->demo = null;
        $this->demoID = -1;
    }


    /**
     * change order status
     * 
     * @return void
     */
    public function demoOrder($property)
    {
        if($property === $this->currentOrderProperty)
        {
           $this->currentOrder = ($this->currentOrder === 'asc') ? 'desc' : (($this->currentOrder === 'desc') ? '' : 'asc');
        }
        else
        {
            $this->currentOrder = 'asc';
            $this->currentOrderProperty = $property;
        }
    }


    /**
     * call after clicking "Edit" in demo page
     * 
     * @return redrect route 
     */
    public function redirecToDemo($demoID)
    {
        if(!auth()->user()->hasPermission(2)){
            session()->flash('flash.banner', "You do not have permission to edit demo!");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::REDIRECT_ROUTE);
        }
        
        return redirect()->route('demos',['demoID' => $demoID]);
    }

}
