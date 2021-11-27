<?php

namespace App\Http\Livewire\Demos;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\Demo;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class CreateForm extends Component
{

    use WithFileUploads;


    /**
     * 
     * @var string
     */
    public $name;


    /**
     * 
     * @var string
     */
    public $description;


    /**
     * 
     * @var integer
     */
    public $category_id;


    /**
     * 
     * @var file
     */
    public $plotFile;


    /**
     * 
     * @var file
     */
    public $input_file;


    /**
     * 
     * @var file array
     */
    public $output_files = [];


    /**
     * 
     * @var file array
     */
    public $input_files = [];


    /**
     * for displying only
     * @var Category array
     */
    public $categories = [];


    /**
     * 
     * @var boolean
     */
    public $status = 0;


    /**
     * 
     * @var boolean
     */
    public $next = false;
    

    public $uploadFields = [];



    /**
     * 
     * @var Demo
     */
    public $demo;



    /**
     * 
     * @var boolean
     */
    public $displayEditable;


    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'demos.create-form';
    

    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'demos';


    /**
     * @var string Redirect to itself
     */
    const FAIL_ROUTE = 'demos.create';



    /**
     * initilize properties
     * 
     * @return void
     */
    public function mount()
    {
        $categories = Category::all();
        foreach ($categories as $category)
        {
            $this->categories[$category->id] =  $category->name;
        }
        if(count($categories) != 0)
            $this->category_id = $categories[0]->id; 
    }


    /**
     * render demo page
     * 
     * @return view Demos section
     */
    public function render()
    {
        $output_file_names = [];
        if($this->next)
        {
            for ($x = 0; $x < count($this->output_files); $x++) 
            {
                $output_file_names[$x] = $this->output_files[$x]->getClientOriginalName();
            }

            $this-> displayEditable = true;
            foreach ($this->input_files as $fileType => $file)
            {
                if(!isset($this->input_files[$fileType]))
                {
                    $this->displayEditable = false;
                    break;
                }         
            }
            if(count($output_file_names) < 1)
            {
                $this->displayEditable = false;
            }

        }

        return view(self::COMPONENT_TEMPLATE, [
            'output_file_names' => $output_file_names
        ]);
    }



    /**
     * add demo page
     * 
     * @return void
     */
    public function add(Request $request)
    {
        $this->validate([
            'output_files.*' => 'file',
            'input_files.*' => 'required|file',
            'plotFile' => 'image|nullable',
            'status' => 'required|boolean'
        ]);

        $input_json = [
            'fileName' => []
        ];
        foreach ($this->input_files as $type => $file)
        {
            $input_json['fileName'][$type] = $file->getClientOriginalName();
            $input_json[$type] = $file->store('demos/'.$this->demo->id,'public');
        }$input_json = json_encode($input_json);


        $output_json = [
            'fileName' => [],
        ];
        foreach ($this->output_files as $file)
        {
            $uniqueFileName = Str::uuid().$file->getClientOriginalName();
            array_push($output_json['fileName'],$uniqueFileName);
            $output_json[$uniqueFileName] = $file->store('demos/'.$this->demo->id,'public');
        }$output_json = json_encode($output_json);

        if(isset($this->plotFile))
        {
            $this->demo->plot_path = $this->plotFile->store('demos/'.$this->demo->id,'public');
        }

        $this->demo->input_file_json = $input_json;
        $this->demo->output_file_json = $output_json;
        $this->demo->status = $this->status; 

        $status = $this->demo->save();   

        $msg =  $status ? 'Demo successfully created!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) 
        {    
            return redirect()->route(self::REDIRECT_ROUTE);
        }

    }

    /**
     * register new demo
     * 
     * @return void
     */
    public function registerDemo(Request $request)
    {
        $data = $this->validate([
            'name' => 'required|max:255|unique:demos,name',
            'description' => 'required|max:255',
            'category_id' => 'required|integer',
            'input_file' => 'file|required',
        ]);
     
        try 
        {
            $this->readFileFrom($this->input_file->getRealPath());
            $data['input_file_json'] = '{ "fileName" : [] }';
            $data['output_file_json'] = '{ "fileName" : [] }';
            $data['input_property_json'] = json_encode($this->uploadFields);
        } 
        catch (\Exception $e) 
        {
            session()->flash('flash.banner', $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::FAIL_ROUTE);
        }
        
        
        $this->demo = Demo::create($data);

       
        $msg =  $this->demo ? 'Demo successfully created!' : 'Ooops! Something went wrong.';
        $flag = $this->demo ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if (!$this->demo) 
        {    
            return redirect()->route(self::FAIL_ROUTE);
        }
        $this->initInputFiles();
        $this->next = true;  
        Storage::disk('public')->makeDirectory('demos/'.$this->demo->id);
    }



    /**
     * read and parse input file
     * 
     * @return void
     */
    private function readFileFrom($path)
    {
        $myfile = fopen($path, "r") or die("Unable to open file!");
        $pattern = "/^(_INPUT)/i";
        $lineNum = 1;
        while(! feof($myfile))
        {
            $line = fgets($myfile);
            if(preg_match($pattern, $line))
            {
               
                $inputProperties = explode("/",$line);
                if(count($inputProperties) != 3)
                {
                    throw new \Exception('Cannot parse input parameter at line '.$lineNum); 
                }
                $fileType = $inputProperties[1];
                $inputProperties = explode(".",trim($inputProperties[2]," \r\n\t"));
                if(count($inputProperties) < 2)
                {
                    throw new \Exception('Cannot parse file extension at line '.$lineNum); 
                }
                $fileExtension = end($inputProperties);
                $this->uploadFields[$fileType] = $fileExtension;
            }
            $lineNum++;
        }
        fclose($myfile);
    } 


    /**
     * initilize inputs file upload fields
     * 
     * @return void
     */
    private function initInputFiles()
    {
        if(isset($this->uploadFields))
        {
            foreach ($this->uploadFields as $fileType => $extension){
                $this->input_files[$fileType] = null;
            }
        }
    }

}
