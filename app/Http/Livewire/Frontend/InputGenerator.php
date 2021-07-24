<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class InputGenerator extends Component
{

    public $inputInfo;

    public $inputValue;

    public $enableSeq;

    public $childrenNum;

    public $tempChildren;


    public function mount()
    {
        $this->loadInputInfo();
        $this->initSys();
        //dd($this->childrenNum);
    }


    public function render()
    {
        //dd($this->inputValue);
        return view('frontend.input-generator');
    }

    private function loadInputInfo()
    {
        $content = '';
        if($stream = fopen(base_path('input-template.json'), "r"))
            while(($line=fgets($stream))!==false){
                $content .= $line;
            }
        else
            throw new \Exception('Cannot Load the Input Template File !');
        $this->inputInfo = json_decode($content, true); 
    }

    private function initSys(){
        if(isset($this->inputInfo['properties']))
        {
            foreach($this->inputInfo['properties'] as $key => $property)
            {                        
                $this->inputValue[$key]['value'] = (isset($property['default'])) ? $property['default'] : null;
                $this->enableSeq[$key]['main'] = (isset($property['_enabled']))? false : true;  
                $this->childrenNum[$key] = (isset($property['children']))? 1 : 0;        

                if(isset($property['children']))
                {
                    for($i = 0 ; $i < $this->childrenNum[$key] ; $i++)
                    {
                        foreach($property['children'] as $cKey => $cProperty)
                        {                    
                            $this->inputValue[$key][$cKey.'-'.$i] = (isset($cProperty['default'])) ? $cProperty['default'] : null;
                            $this->enableSeq[$key][$cKey.'-'.$i] = (isset($cProperty['_enabled']))? false : true;
                        } 
                    }
                    
                }
            }
            $this->updatedInputValue();
            $this->refreshCNum();
        }
    }

    public function updatedInputValue()
    {
        if(!(isset($this->enableSeq) && isset($this->inputValue) && isset($this->inputInfo)))
        {
            return;
        }
            
        foreach ($this->enableSeq as $key => &$property)
        {
            $inputProperty = $this->inputInfo['properties'][$key];
            foreach ($property as $cKey => &$enable)
            {                  
                $enabledInfo = null;
                if($cKey === "main" && isset($inputProperty['_enabled']))
                    $enabledInfo = $inputProperty['_enabled'];
                   
                else if(isset($inputProperty['children']) && isset($inputProperty['children'][explode('-',$cKey)[0]]['_enabled']))
                    $enabledInfo = $inputProperty['children'][explode('-',$cKey)[0]]['_enabled'];

                if(isset($enabledInfo))
                {
                    if(is_array($enabledInfo['value']))
                    {
                        $enable = false;
                        foreach ($enabledInfo['value'] as $value)
                        {
                            if($value === $this->inputValue[$enabledInfo['property']]['value'])
                            {
                                $enable = true;
                                return;
                            }
                        }
                    }
                    else
                        $enable = ($enabledInfo['value'] === $this->inputValue[$enabledInfo['property']]['value'])? true : false;   
                }
            }
        }

        $this->refreshCNum();
    }

    private function refreshCNum()
    {
        foreach($this->inputInfo['properties'] as $key => $property)
        {                        
            $oldChildrenNum = $this->childrenNum[$key];
            if(isset($property['_numChildren']))
            {
                $nestedChild = $this->prasenumChildren($property['_numChildren']);
                $nestedValue = $this->inputValue;
                for ($i = 0; $i < count($nestedChild); $i++)
                {
                    $nestedValue = $nestedValue[$nestedChild[$i]];
                }
                $this->childrenNum[$key] = (int)$nestedValue;
            }

            if(isset($property['children']))
            {
                $start = ($oldChildrenNum <= $this->childrenNum[$key])? $oldChildrenNum : 0;
                if($start == 0)
                {
                    $oldInputValue = $this->inputValue[$key]['value'];
                    unset($this->inputValue[$key]);
                    $this->inputValue[$key]['value'] = $oldInputValue;

                    $oldEnableSeq = $this->enableSeq[$key]['main'];
                    unset($this->enableSeq[$key]);
                    $this->enableSeq[$key]['main'] = $oldEnableSeq;
                }
                    
                for($i = $start ; $i < $this->childrenNum[$key] ; $i++)
                {
                    foreach($property['children'] as $cKey => $cProperty)
                    {                    
                        $this->inputValue[$key][$cKey.'-'.$i] = (isset($cProperty['default'])) ? $cProperty['default'] : null;
                        $this->enableSeq[$key][$cKey.'-'.$i] = (isset($cProperty['_enabled']))? false : true;
                    } 
                }
                
            }
        }
    }

    private function prasenumChildren($inputString)
    {
        return explode(".",trim($inputString," \r\n\t"));
    }

    public function generateChildren()
    {

    }

    public function generateFile()
    {
        $filename = Storage::disk('public')->path('utils'. '/' . 'input.input');
        $writeStr = '';

        foreach ($this->inputInfo['properties'] as $key => $property)
        {
            
            $writeStr .= $property['title']."\r\n";
            foreach ($this->inputValue[$key] as $cKey => $value)
            {
                if(is_null($value))
                {
                    continue;
                }
                $val = is_array($value) ? $value : [$value];
                $writeStr .= sprintf("%s\r\n", implode(' ', $val));
            }
            $writeStr .= "\r\n";
        }
        file_put_contents($filename,$writeStr);
    }
}
