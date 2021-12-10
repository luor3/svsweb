<?php

namespace App\Http\Livewire\Frontend\Jobs;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\Job;
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
    public $user;
    
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
     * @var string
     */
    public $configuration;
    
    /**
     * 
     * @var integer
     */
    public $category_id;


    /**
     * 
     * @var file
     */
    public $plot_file;


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
     * default is 1
     */
    public $status = 1;


    /**
     * 
     * @var boolean
     */
    public $next = false;
    

    public $uploadFields = [];


    /**
     * 
     * @var Job
     */
    public $job;

    /**
     * 
     * @var boolean
     */
    public $displayEditable;


    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'frontend.jobs.create-form';
    

    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'jobs';


    /**
     * @var string Redirect to itself
     */
    const FAIL_ROUTE = 'jobs.create';

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
     * render job page
     * 
     * @return view jobs section
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
     * add job page
     * 
     * @return void
     */
    public function add(Request $request)
    {
        $this->validate([
            'output_files.*' => 'file',
            'input_files.*' => 'required|file',
            'status' => 'required|boolean'
        ]);

        $input_json = [
            'fileName' => []
        ];
        foreach ($this->input_files as $type => $file)
        {
            $input_json['fileName'][$type] = $file->getClientOriginalName();
            $input_json[$type] = $file->store('jobs/'.$this->job->id,'public');
        }
        $input_json = json_encode($input_json);

        $output_json = [
            'fileName' => [],
        ];
        /*
        foreach ($this->output_files as $file)
        {
            $uniqueFileName = Str::uuid().$file->getClientOriginalName();
            array_push($output_json['fileName'],$uniqueFileName);
            $output_json[$uniqueFileName] = $file->store('jobs/'.$this->job->id,'public');
        }
        $output_json = json_encode($output_json);

        if(isset($this->plot_file))
        {
            $this->job->plot_path = $this->plot_file->store('jobs/'.$this->job->id,'public');
        }
        */
        $this->job->configuration = $input_json;
        $this->job->status = $this->status; 

        $result = job::find(['id'=>$this->job->id])->toArray();
        $result[0]['configuration'] = json_decode($result[0]['configuration'],true);
        $result[0]['configuration']['input_file_json'] = $input_json;
        $result[0]['configuration']['output_file_json'] = $output_json;
        $result[0]['configuration'] = json_encode($result[0]['configuration']);
        $this->job->configuration = $result[0]['configuration'];
        $status = $this->job->save();   

        $msg =  $status ? 'job successfully created!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) 
        {    
            return redirect()->route(self::REDIRECT_ROUTE);
        }

    }



    /**
     * register new job
     * 
     * @return void
     */
    public function registerJob(Request $request)
    {    
        
        $data = $this->validate([
            'name'=>'required|max:255',  
            'description' => 'required|max:255',
            'category_id' => 'required|integer',
            'input_file' => 'file',
            'description' => 'required|max:255',
        ]);
        $user = auth()->user();
        $data['user'] = $user->id;

        try 
        {   

            $this->readFileFrom($this->input_file->getRealPath());
            $input_file_json = '{ "fileName" : [] }';
            $output_file_json = '{ "fileName" : [] }';
            $input_property_json = json_encode($this->uploadFields);
            $map = [
            'input_file_json'=>$input_file_json,
            'output_file_json'=>$output_file_json,
            'input_property_json'=>$input_property_json,
           ];
           $data['configuration'] = json_encode($map);
                      
        } 
        catch (\Exception $e) 
        {
            session()->flash('flash.banner', $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::FAIL_ROUTE);
        }
        unset($data['input_file']);

        $this->job = job::create($data);
        
        $msg =  $this->job ? 'Job successfully created!' : 'Ooops! Something went wrong.';
        $flag = $this->job ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if (!$this->job) 
        {    
            return redirect()->route(self::FAIL_ROUTE);
        }
        $this->initInputFiles();
        $this->next = true;  
        Storage::disk('public')->makeDirectory('jobs/'.$this->job->id);
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
