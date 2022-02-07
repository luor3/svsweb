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
    const REDIRECT_ROUTE = 'userprofile';


    /**
     * @var string Redirect to itself
     */
    const FAIL_ROUTE = 'userprofile';

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
        return view(self::COMPONENT_TEMPLATE);
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
        
        $input_json = json_decode($this->job->configuration,true);


        foreach ($this->input_files as $type => $file)
        {
            $input_json['input_file_json']['fileName'][$type] = $file->getClientOriginalName();
            //$path = $file->storeAs('public/jobs/'.$this->job->id, $file->getClientOriginalName());
            $input_json['input_file_json'][$type] = $file->storeAs('public/jobs/'.$this->job->id,$file->getClientOriginalName());

            
        }
        //dd($input_json);
        $input_json = json_encode($input_json);

        $output_json = [
            'fileName' => [],
        ];

        $this->job->configuration = $input_json;
        $this->job->status = $this->status; 

        $status = $this->job->save();   

        $msg =  $status ? 'job successfully created!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) 
        {    
            return redirect()->route(self::REDIRECT_ROUTE, ['currentModule' => "jobs"]);
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
            $map = [
            'input_file_json'=>$input_file_json,
            'output_file_json'=>$output_file_json,
            'input_property_json'=>$this->uploadFields,
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

        $originalname = $this->input_file->getClientOriginalName();
        //dd($originalname);
        Storage::disk('public')->makeDirectory('jobs/'.$this->job->id);
        //Storage::disk('public')->put($originalname, $this->input_file,'jobs/'.$this->job->id);
        $input_file_path = $this->input_file->storeAs('public/jobs/'.$this->job->id, $originalname);
        $input_json = json_decode($this->job->configuration,true);
        $fileName_json = json_decode($input_json['input_file_json'] , true);
        $fileName_json["fileName"]["input"] = $originalname;
        $input_json['input_file_json'] = $fileName_json;

        $input_json['input_file_json']['input'] = $input_file_path;
        $this->job->configuration = json_encode($input_json);
        $this->job->save();
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
