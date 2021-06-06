<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class UpdateForm extends Component
{
    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'pages.update-form';
    
    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'pages';
    
    /**
     * 
     * @var integer
     */
    public $pid;
    
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
     * 
     * @var Page
     */
    public $page;
    
    /**
     * 
     * @var boolean
     */
    public $confirmingPageDeletion = false;
    

    /**
     * 
     * @return view
     */
    public function render()
    {
        if (isset($this->page)) {
            $this->link = $this->page->link;
            $this->title = $this->page->title;
            $this->description = $this->page->description;
            $this->keywords = $this->page->keywords;
            $this->content = $this->page->content;
            $this->status = $this->page->status;
            $this->pid = $this->page->id;
        }
        
        return view(self::COMPONENT_TEMPLATE);
    }
    
    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'link' => 'required|max:255|unique:pages,link,'.$this->pid,
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'keywords' => 'required|max:255',
            'content' => 'min:0',
            'status' => 'required',
        ];
    }
    
    /**
     * update page
     * 
     * @return view Pages section
     */
    public function update()
    {
        $this->validate();
        
        if ($this->page) {
            $this->page->link = $this->link;
            $this->page->title = $this->title;
            $this->page->description = $this->description;
            $this->page->keywords = $this->keywords;
            $this->page->content = $this->content;
            $this->page->status = $this->status;
            $status = $this->page->save();
            
            $this->emit('saved', $status);
        }
    }
    
    /**
     * delete page
     * 
     * @return view
     */
    public function delete()
    {
        if ($this->page && $this->page->id) {
            $deleted = $this->page->delete();
            
            $msg =  $deleted ? 'Page successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($deleted) {    
                return redirect()->route(self::REDIRECT_ROUTE);
            }
        }
    }
}