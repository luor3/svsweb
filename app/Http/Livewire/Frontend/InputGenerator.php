<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Redirector;

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
     * initialize the page variables and system
     * @return void
     */
    public function mount()
    {
        $this->loadInputInfo();
        $this->initSys();
        //dd($this->validationRules);
    }


    /**
     * render the page
     * @return void
     */
    public function render()
    {
        //dd($this->inputValue);
        return view('frontend.input-generator');
    }


    /**
     * load the input-template.json file
     * @return void
     */
    private function loadInputInfo()
    {
        $content = '';
        if($stream = fopen(base_path('input-template.json'), "r"))
        {
            while(($line=fgets($stream))!==false){
                $content .= $line;
            }
        }         
        else
        {
            throw new \Exception('Cannot Load the Input Template File !');
        }       
        $this->inputInfo = json_decode($content, true); 
    }


    /**
     * initialize the page
     * @return void
     */
    private function initSys(){
        if(isset($this->inputInfo['properties']))
        {
            foreach($this->inputInfo['properties'] as $key => $property)
            {                        
                $this->inputValue[$key]['main'] = (isset($property['default'])) ? $property['default'] : null;
                if(isset($property['validationRule']))
                {
                    $this->validationRules["inputValue.".$key.".main"] = $property['validationRule'];
                }
               


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
                            
                        }
                        
                        if(isset($cProperty['_enabled']))
                        {
                            $this->enableSeq[$key][$cKey] = false; 
                        }

                        if(isset($cProperty['_repeat']))
                        {
                            $this->repeatNum[$key]['children'] = [];
                            $this->validationRules["inputValue".".".$key."."."children"."."."*".".".$cKey."."."*"] = $cProperty['validationRule'];
                        }
                        else
                        {
                            $this->validationRules["inputValue".".".$key."."."children"."."."*".".".$cKey] = $cProperty['validationRule'];
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


    /**
     * This function will be called automatically 
     * once variable inputValue is changed
     * @return void
     */
    public function updatedInputValue()
    {
        if(!(isset($this->enableSeq) && isset($this->inputValue) && isset($this->repeatNum)))
        {
            return;
        }
        $this->refreshRepeatNum();
        $this->refreshESeq();
    }



    /**
     * update the status of enabled properties
     * @return void
     */
    private function refreshESeq()
    {
        foreach ($this->enableSeq as $key => &$property)
        {
            $inputProperty = $this->inputInfo['properties'][$key];
            foreach ($property as $cKey => &$enable)
            {                  
                $enabledInfo = null;
                $enabledInfo = ($cKey === "main") ? $inputProperty['_enabled'] : $inputProperty['children'][$cKey]['_enabled'];
                if(isset($enabledInfo))
                {
                    $array_val = is_array($enabledInfo['value']) ? $enabledInfo['value'] : [$enabledInfo['value']];
                    $enable = (in_array($this->inputValue[$enabledInfo['property']]['main'] , $array_val)) ? true : false;                       
                }
            }
        }
    }



    /**
     * update the status of repeat numbers
     * @return void
     */
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
                    $tempInt = ($tempInt) ? $tempInt[$index] : $this->inputValue[$index];
                }
                $repeatSet['main'] = ((int)$tempInt < 0) ? 0 : (int)$tempInt;


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


                    for($i = $oldChildrenNum ; $i < $newCNum  ; $i++)
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
                    for($i = $oldChildrenNum - 1 ; $i >= $newCNum ; $i--)
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
                            for($j = $oldCRepeatNum ; $j < $repeatValue  ; $j++)
                            {
                                array_push($this->inputValue[$pName]['children'][$i][$cName], (isset($childrenInstance['default'])) ? $childrenInstance['default'] : null);
                            }
                        }

                        //clear
                        else
                        {
                            for($j = $oldCRepeatNum - 1 ; $j >= $repeatValue ; $j--)
                            {
                                array_pop($this->inputValue[$pName]['children'][$i][$cName]);
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * generate the input file and 
     * response the streamed file to user
     * @return File
     */
    public function generateFile()
    {
        $this->validate();
        
        $writeStr = '';
        foreach ($this->inputInfo['properties'] as $key => $property)
        {
            $writeStr .= $property['title'].PHP_EOL;
            foreach ($this->inputValue[$key] as $vKey => $value)
            {
                if($vKey === "main")
                {
                    if(is_null($value) || $value == "")
                    {
                        if(isset($property['default']))
                        {
                            $value = $property['default'];
                        }
                        else
                        {
                            continue;
                        }
                    }
                    
                    $val = is_array($value) ? $value : [$value];
                    $this->fileGenerationHelper($writeStr, $val, $key);                  
                }
                else if($vKey === "children")
                {
                    foreach ($value as $cIndex => $cValues)
                    {
                        foreach($cValues as $cName => $cValue)
                        {
                            if(is_null($cValue) || $cValue == "")
                            {
                                if(isset($property['children'][$cName]['default']))
                                {
                                    $cValue = $property['children'][$cName]['default'];
                                }
                                else
                                {
                                    continue;
                                }
                            }
                            
                            $val = is_array($cValue) ? $cValue : [$cValue];
                            $this->fileGenerationHelper($writeStr, $val, $key, $cName);                                                                          
                        }                       
                    }
                }    
            }
            $writeStr .= "\r\n";
        }
        return response()->streamDownload(function () use($writeStr)
        {
            echo $writeStr;
        }, 'input.input');
    }


    /**
     * change the property display format 
     * in the final input file
     * @return void
     */
    private function fileGenerationHelper(&$writeStr, $val, $pName, $cName = null)
    {             
        $property = $this->inputInfo['properties'][$pName];

        $fileDisplayType = "NONE";
        $display = true;
        if(is_null($cName))
        {
            if(isset($property['fileDisplay']))
            {
                $fileDisplayType = $property['fileDisplay'];
            }     
            
            if(isset($property['displayOnEnable']))
            {
                if($property['displayOnEnable'])
                {
                    $display = $this->enableSeq[$pName];
                }
            }
        }
        else
        {
            if(isset($property['children'][$cName]['fileDisplay']))
            {
                $fileDisplayType = $property['children'][$cName]['fileDisplay'];
            }    
            
            if(isset($property['children'][$cName]['displayOnEnable']))
            {
                if($property['children'][$cName]['displayOnEnable'])
                {
                    $display = $this->enableSeq[$pName][$cName];
                }
            }
        }
        
        if($display)
        {
            switch ($fileDisplayType) 
            {
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
                default:
                    $writeStr .= sprintf("%s".PHP_EOL, implode(' ', $val));
            }
        }                           
    }
}
