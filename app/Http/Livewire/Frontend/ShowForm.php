<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Category;
use App\Models\Demo;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class ShowForm extends Component
{
    use WithPagination;


    /**
     * 
     * @var array
     */
    public $categories = [];


    /**
     * 
     * @var integer
     */
    public $searchCategory = -1;


    /**
     * 
     * @var array
     */
    protected $queryString = [
        'searchCategory'
    ];


    /**
     * 
     * @var integer
     */
    public $demo_id;


    /**
     * 
     * @var integer
     */
    public $displayLink = -1;
    

    /**
     * 
     * @var integer
     */
    public $pageNum = 2;


    /**
     * reset page number before updating category search
     * 
     * @return void
     */
    public function updatingSearchCategory()
    {
        $this->resetPage();
    }


    /**
     * initialize the front demo page
     * 
     * @return void
     */
    public function mount(){
        $categories = Category::all();
        foreach ($categories as $category){
            $this->categories[$category->id] =  $category->name;
        }
    }


    /**
     * reset page number before updating category search
     * 
     * @return View Front Demo section
     */
    public function render()
    {
        $demos = Demo::where('status', '=', 1);
        if($this->searchCategory != -1)
            $demos = $demos->where('category_id', '=', $this->searchCategory);
        return view('frontend.show-form',[
            'demos' => $demos->paginate($this->pageNum),
        ]);
    }


    /**
     * generate input and output files for given demo
     * 
     * @return void
     */
    public function generateZip(Demo $demo){
        $input_file_json = json_decode($demo->input_file_json,true);
        $output_file_json = json_decode($demo->output_file_json,true);


        $zip_input_file = 'inputs.zip';
        $zip_output_file = 'outputs.zip';
        $zip_input = new \ZipArchive();
        $zip_output = new \ZipArchive();
        $zip_input_path = Storage::disk('public')->path('demos'. '/' . $demo->id . '/' . $zip_input_file);
        $zip_output_path = Storage::disk('public')->path('demos'. '/' . $demo->id . '/' . $zip_output_file);

        
        if ($zip_input->open($zip_input_path, \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE) !== true) {
            dd ("An error occurred creating input ZIP file.");
        }

        if ($zip_output->open($zip_output_path, \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE) !== true) {
            dd ("An error occurred creating output ZIP file.");
        }


        foreach ($input_file_json['fileName'] as $fileType => $fileName){
            $zip_input->addFile(Storage::disk('public')->path($input_file_json[$fileType]),$fileName);
        }

        foreach ($output_file_json['fileName'] as $fileName){
            $zip_output->addFile(Storage::disk('public')->path($input_file_json[$fileType]),substr($fileName, 36));
        }

        $zip_input->close();
        $zip_output->close();

        $this->displayLink = $demo->id;
    }

}
