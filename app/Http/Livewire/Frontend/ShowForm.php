<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Category;
use App\Models\Demo;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use \ZipStream\Option\Archive;
use \ZipStream\ZipStream;


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


    public function showVTKmodel($vtkPath) 
    {
        redirect()->route('vtk-visualizer', ['vtkPath'=> $vtkPath]);
    }

    /**
     * generate input and output files on fly for given demo
     * 
     * @return void
     */
    public function downloadFile(Demo $demo, $isInput)
    {
        $file_json = json_decode($isInput ? $demo->input_file_json : $demo->output_file_json , true);

        return response()->streamDownload(function () use($file_json,$isInput) 
        {
            $options = new Archive();
            $options->setSendHttpHeaders(false);
            $zip = new ZipStream( $isInput ? "input.zip" : "output.zip", $options);
            foreach ($file_json['fileName'] as $fileType => $fileName)
            {
                $zip->addFileFromPath($isInput ? $fileName : substr($fileName, 36), 
                    Storage::disk('public')->path($file_json[$isInput ? $fileType : $fileName])
                );
            }
            $zip->finish();
        }, $isInput ? "input.zip" : "output.zip");
    }

}
