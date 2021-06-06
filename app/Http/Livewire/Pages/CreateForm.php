<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;
use App\Models\Page;

class CreateForm extends Component
{
    /**
     * 
     * @var string
     */
    public $link;
    
    /**
     * 
     * @var string
     */
    public $title;
    
    /**
     * 
     * @var string
     */
    public $description;
    
    /**
     * 
     * @var string
     */
    public $keywords;
    
    /**
     * 
     * @var string
     */
    public $content;
    
    /**
     * 
     * @var boolean
     */
    public $status;
    
    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'pages.create-form';
    
    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'pages';

    /**
     * 
     * @return view
     */
    public function render()
    {
        return view(self::COMPONENT_TEMPLATE);
    }
    
    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'link' => 'required|max:255|unique:pages',
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'keywords' => 'required|max:255',
            'content' => 'min:0',
            'status' => 'required',
        ];
    }
    
    /**
     * add new page
     * 
     * @return view Pages section
     */
    public function add()
    {
        $data = $this->validate();
        
        $status = Page::create($data);
       
        $msg =  $status ? 'Page successfully created!' : 'Ooops! Something went wrong.';
        $flag = $status ? 'success' : 'danger';

        session()->flash('flash.banner', $msg);
        session()->flash('flash.bannerStyle', $flag);
        
        if ($status) {    
            return redirect()->route(self::REDIRECT_ROUTE);
        }
    }
}
