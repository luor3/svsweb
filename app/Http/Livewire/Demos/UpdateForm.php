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
    public $demo_id = -1;



    /**
     * 
     * @var Category array
     */
    public $categories = []; 


    /**
     * 
     * @var File array
     */
    public $output_files;


    /**
     * 
     * @var File array
     */
    public $input_files;



    public $uploadFields = [];

    /**
     * 
     * @var File
     */
    public $plot_file;


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
    public $output_file_json = [];



    /**
     * 
     * @var array
     */
    public $input_file_json = [];



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
        'demo_id' => ['except' => -1]
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
            'output_files.*' => 'file',       
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
        if($this->demo_id !== -1)
        {
            $this->registerDemo($this->demo_id, false);
        }
            
    }


    /**
     * render the demo
     * @return View Demo section
     */
    public function render()
    {  
        $demos = [];
        if(isset($this->demo) && $this->demo_id !== -1)
        {

            $this->output_file_json = json_decode($this->demo->output_file_json, true);
            $this->input_file_json = json_decode($this->demo->input_file_json, true); 

            (count($this->output_file_json['fileName']) >= 1  &&
            count($this->uploadFields) == count($this->input_file_json['fileName']))?
            $this->displayEditable = true : $this->displayEditable = false;          
        }
        
        else
        {
            $demos = Demo::where('name', 'like', '%'.$this->nameSearch.'%');

            if($this->categorySearch != -1)
            {
                $demos = $demos->Where('category_id', $this->categorySearch);
            }
            $demos = $demos->paginate($this->pageNum);
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
        $this->validate();
    
        if (isset($this->demo)) 
        { 
            $this->demo->name = $this->demoAttr['name'];
            $this->demo->description = $this->demoAttr['description'];
            $this->demo->category_id = $this->demoAttr['category_id'];
            $this->demo->status = $this->demoAttr['status'];

            try 
            {
                if(isset($this->plot_file))
                {
                    if($this->demo->plot_path)
                    {
                        Storage::disk('public')->delete($this->demo->plot_path);
                    }                
                    $this->demo->plot_path = $this->plot_file->store('demos/'.$this->demo->id,'public');

                    $this->plot_file = null;
                }           
                
                if(isset($this->output_files))
                {
                    
                    foreach ($this->output_file_json['fileName'] as $fileName)
                    {
                        Storage::disk('public')->delete($this->output_file_json[$fileName]);
                    } 

                    $output_json = [
                        'fileName' => [],
                    ];
                    foreach ($this->output_files as $file)
                    {
                        
                        $uniqueFileName = Str::uuid().$file->getClientOriginalName();
                        array_push($output_json['fileName'],$uniqueFileName);
                        $output_json[$uniqueFileName] = $file->store('demos/'.$this->demo->id,'public');
                    }
                    $this->demo->output_file_json  = json_encode($output_json);
                    $this->output_files = null;
                }
                
                foreach ($this->input_files as $fileType => $file)
                {
                    if(isset($this->input_files[$fileType]))
                    {
                        if(isset($input_file_json['fileName'][$fileType]))
                        {
                            Storage::disk('public')->delete($this->input_file_json[$fileType]);
                        }       
                        $this->input_file_json['fileName'][$fileType] = $file->getClientOriginalName();
                        $this->input_file_json[$fileType] = $file->store('demos/'.$this->demo->id,'public');
                    }
                }
                $this->demo->input_file_json = json_encode($this->input_file_json);
                
                
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
            $this->confirmingDemoDeletion = true;
        }
        else
        {
            $this->demo_id = $this->demo->id;
            if (isset($this->demo)) 
            {
                $this->demoAttr['name'] = $this->demo->name;
                $this->demoAttr['description'] = $this->demo->description;
                $this->demoAttr['category_id'] = $this->demo->category_id;
                $this->demoAttr['status'] = $this->demo->status;

                $this->uploadFields = json_decode($this->demo->input_property_json,true);
                foreach ($this->uploadFields as $fileType => $extension){
                    $this->input_files[$fileType] = null;
                }       
            } 
        }
    }

    public function clearDemo()
    {
        $this->demo = null;
        $this->demo_id = -1;
    }

}
