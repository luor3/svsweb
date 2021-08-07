<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class InputGenerator extends Component
{

    /**
     * 
     * @return array
     */
    public $inputInfo;


    /**
     * 
     * @return array
     */
    public $inputValue;


    /**
     * 
     * @return array
     */
    public $enableSeq;


    /**
     * 
     * @return array
     */
    public $repeatNum;


    /**
     * 
     * @return array
     */
    public $validationRules;

    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return $this->validationRules;
    }


    /**
     * initialize 
     * @return void
     */
    public function mount()
    {
        $this->loadInputInfo();
        $this->initSys();
        //dd($this->validationRules);
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
                $this->inputValue[$key]['main'] = (isset($property['default'])) ? $property['default'] : null;
                $this->validationRules["inputValue.".$key.".main"] = $this->validationHelper($property['type']);


                if(isset($property['_enabled']))
                {
                   $this->enableSeq[$key]['main'] = false;   
                }
                
                if(isset($property['_repeat']))
                {
                    $this->repeatNum[$key]['main'] = 0;
                }
                        

                if(isset($property['children']))
                {
                    $this->inputValue[$key]['children'] = [];
                    $childSet = [];

                    foreach($property['children'] as $cKey => $cProperty)
                    {                    
                                    
                        if(!isset($property['_repeat']))
                        {
                            $childSet[$cKey] = (isset($cProperty['default'])) ? $cProperty['default'] : null;
                            $this->validationRules["inputValue.".$key.".children.0.".$cKey] = $this->validationHelper($cProperty['type']);
                        }
                        
                        if(isset($cProperty['_enabled']))
                        {
                            $this->enableSeq[$key][$cKey] = false; 
                        }

                        if(isset($cProperty['_repeat']))
                        {
                            $this->repeatNum[$key]['children'] = [];
                        }
       
                    }
                    if(!isset($property['_repeat']))
                    {
                        array_push($this->inputValue[$key]['children'], $childSet);
                    }           
                }               
            }
            $this->updatedInputValue();
        }
    }

    public function updatedInputValue()
    {
        if(!(isset($this->enableSeq) && isset($this->inputValue) && isset($this->repeatNum)))
        {
            return;
        }
        $this->refreshRepeatNum();
        $this->refreshESeq();
    }


    private function refreshESeq()
    {
        foreach ($this->enableSeq as $key => &$property)
        {
            $inputProperty = $this->inputInfo['properties'][$key];
            foreach ($property as $cKey => &$enable)
            {                  
                $enabledInfo = null;
                if($cKey === "main")
                    $enabledInfo = $inputProperty['_enabled'];          
                else
                    $enabledInfo = $inputProperty['children'][$cKey]['_enabled'];

                if(isset($enabledInfo))
                {
                    $array_val = is_array($enabledInfo['value']) ? $enabledInfo['value'] : [$enabledInfo['value']];
                    $enable = (in_array($this->inputValue[$enabledInfo['property']]['main'] , $array_val)) ? true : false;                       
                }
            }
        }
    }


    private function refreshRepeatNum()
    {
        if(!isset($this->repeatNum)) return;

        foreach ($this->repeatNum as $pName => &$repeatSet)
        {
            $oldChildrenNum = $repeatSet['main'];

            //update new children num
            if(isset($repeatSet['main']))
            {
                $valuePath = $this->inputInfo['properties'][$pName]['_repeat'];
                $valuePath_array = explode(".",trim($valuePath," \r\n\t"));
                $tempInt = null;
                foreach ($valuePath_array as $index)
                {
                    if($tempInt)
                        $tempInt = $tempInt[$index];
                    else
                        $tempInt = $this->inputValue[$index];
                }
                $repeatSet['main'] = ((int)$tempInt < 0) ? 0 : (int)$tempInt;
            }
                 
             //children generation
            if(isset($repeatSet['main']))
            {
                $newCNum = $repeatSet['main'];
                

                //add child
                if($oldChildrenNum <= $newCNum)
                {
                    $childSet = [];
                    $repeatChildSet = [];
                    foreach ($this->inputInfo['properties'][$pName]['children'] as $key => $value)
                    {
                        if(!isset($value['_repeat']))
                        {
                            $childSet[$key] = (isset($value['default'])) ? $value['default'] : null;
                        }
                        else
                        {
                            $childSet[$key] = [];
                            $repeatChildSet[$key] = 0;        
                        } 
                    }


                    for($i = 0 ; $i < ($newCNum - $oldChildrenNum) ; $i++)
                    {
                        array_push($this->inputValue[$pName]['children'], $childSet);

                        if(count($repeatChildSet) > 0)
                        {
                            array_push($this->repeatNum[$pName]['children'], $repeatChildSet);
                        }
                    }
                }
                //clear child
                else
                {
                    for($i = 0 ; $i < ($oldChildrenNum - $newCNum) ; $i++)
                    {
                        array_pop($this->inputValue[$pName]['children']);
                        if(isset($this->repeatNum[$pName]['children']) && count($this->repeatNum[$pName]['children']) > 0)
                        {
                            array_pop($this->repeatNum[$pName]['children']);
                        }
                    }
                }
            }
            
            //original _repeat operation
            if(isset($repeatSet['children']))
            {
                for($i = 0 ; $i < count($repeatSet['children']) ; $i++ )
                {
                    $childRepeatNum = &$repeatSet['children'][$i];

                    //update child repeat num
                    foreach ($childRepeatNum as $cName => &$repeatValue)
                    {
                        $childrenInstance = $this->inputInfo['properties'][$pName]['children'][$cName];
                        $cRepeatInfo = $childrenInstance['_repeat'];
                        $oldCRepeatNum = $repeatValue;
                        if($cRepeatInfo = preg_replace("/(cself.)/i", '' ,$cRepeatInfo))
                        {
                            $repeatValue = (int)$this->inputValue[$pName]['children'][$i][$cRepeatInfo];
                            $repeatValue = ($repeatValue < 0) ? 0 : $repeatValue;
                        }


                        //add
                        if($oldCRepeatNum <= $repeatValue)
                        {
                            for($j = 0 ; $j < ($repeatValue - $oldCRepeatNum) ; $j++)
                            {
                                array_push($this->inputValue[$pName]['children'][$i][$cName], (isset($childrenInstance['default'])) ? $childrenInstance['default'] : null);
                            }
                        }

                        //clear
                        else
                        {
                            for($j = 0 ; $j < ($oldCRepeatNum - $repeatValue) ; $j++)
                            {
                                array_pop($this->inputValue[$pName]['children'][$i][$cName]);
                            }
                        }
                    }
                }
            }
        }
    }



    public function generateFile()
    {
        $this->validate();
        
        $filename = Storage::disk('public')->path('input.input');
        $writeStr = '';

        foreach ($this->inputInfo['properties'] as $key => $property)
        {
            
            $writeStr .= $property['title'].PHP_EOL;
            foreach ($this->inputValue[$key] as $vKey => $value)
            {
                if(is_null($value))
                {
                    continue;
                }

                if($vKey === "main")
                {
                    $val = is_array($value) ? $value : [$value];
                    $this->fileGenerationHelper($writeStr, $val, $key);                  
                }
                else if($vKey === "children")
                {
                    foreach ($value as $cIndex => $cValues)
                    {
                        foreach($cValues as $cName => $cValue)
                        {
                            $val = is_array($cValue) ? $cValue : [$cValue];
                            $this->fileGenerationHelper($writeStr, $val, $key, $cName);                                                                          
                        }                       
                    }
                }    
            }
            $writeStr .= "\r\n";
        }
        file_put_contents($filename,$writeStr);
    }

    private function fileGenerationHelper(&$writeStr, $val, $pName = null ,$cName = null)
    {             
        $property = $this->inputInfo['properties'][$pName];

        if(isset($property['children'][$cName]['fileDisplay']) || isset($property['fileDisplay']))
        {
            $fileDisplayType = $cName ? $property['children'][$cName]['fileDisplay'] : $property['fileDisplay'];
            switch ($fileDisplayType) {
                case "NOEOL":
                    $writeStr .= sprintf("%s ", implode(' ', $val));
                    break;
                case "VERTICAL":
                    foreach($val as $v)
                    {
                        $writeStr .= $v.PHP_EOL;
                    }
                    break;    
                case "ENABLED":
                    if($cName && $pName && $this->enableSeq[$pName][$cName])
                    {
                        $writeStr .= sprintf("%s".PHP_EOL, implode(' ', $val));
                    }
                    break;   
            }  
        }
        else
        {
            $writeStr .= sprintf("%s".PHP_EOL, implode(' ', $val));
        }                       
    }

    private function validationHelper($type)
    {   
        $result = "Nullable|max:255";

        switch ($type) {
            case 'file':
                $result = "required|max:255";
                break;
            
            case 'point':
                $result = "regex:/^([-+]?[0-9]*\.?[0-9]+)\s([-+]?[0-9]*\.?[0-9]+)\s([-+]?[0-9]*\.?[0-9]+)/|max:255";
                break;

            case 'number':
                $result = "numeric";
                break;
        }

        return $result;
    }
}
