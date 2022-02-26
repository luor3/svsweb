<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class XMLInputGenerator extends Component
{
    
    public $inputInfo;

    public $childNum = array();      

    public $currentID = "inputInfo";

    public $iterator = array();     //pre-order traversal

    public $treeLevel = array();


    public function mount()
    {
        $this->loadInputInfo();
        $this->initSys();
    }
    
    public function render()
    {
        //dd($this->iterator);
        //$this->XMLGenerator();
        return view('frontend.x-m-l-input-generator');
    }


    /**
     * load the input-template.json file
     * @return void
     */
    private function loadInputInfo()
    {
        $content = '';
        if($stream = fopen(base_path('xml-input-template.json'), "r"))
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



    private function initSys()
    {
        $queue = array();
        array_push($queue, $this->inputInfo);
        $level = 0;

        while(count($queue) > 0)
        {
            $element = array_pop($queue);
            
            if($element === 1)  // substree traversal finished
            {
                $this->outLV();
                $this->outLV();
                $level--;
                continue;
            }

            $childIndex = array_pop($this->childNum);
            $this->inLV($childIndex);


            /****************************** Code Start ******************************/
            $this->iterator[$this->currentID] = $element;
            if(isset($element['children']))
                $this->iterator[$this->currentID]['children'] = [];

            $this->treeLevel[$this->currentID] = $level;
            /****************************** Code End ******************************/


            if(isset($element['children'])){
                array_push($queue, 1); 
                $this->inLV('children');
                $level++;

                for($i = count($element['children']) - 1; $i >= 0; $i--)
                {
                    array_push($queue, $element['children'][$i]); 
                    array_push($this->childNum, $i);  
                }
            }
            else
            {
                $this->outLV();
            }
        }
    }

    public function XMLGenerator()
    {
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        $res = xmlwriter_set_indent_string($xw, ' ');

        xmlwriter_start_document($xw, '1.0', 'UTF-8');

        

        $queue = array();
        array_push($queue, $this->inputInfo);
        $level = 0;

        while(count($queue) > 0)
        {
            $element = array_pop($queue);
            
            if($element === 1)
            {
                $this->outLV();
                $this->outLV();
                $level--;
                xmlwriter_end_element($xw);
                continue;
            }

            $childIndex = array_pop($this->childNum);
            $this->inLV($childIndex);


            /****************************** Code Start ******************************/
            xmlwriter_start_element($xw, $element['label']);
            if(isset($element['value']))
            {
                xmlwriter_text($xw, $element['value']);
            }

            if(isset($element['attributes']))
            {
                foreach($element['attributes'] as $attr => $attr_val)
                {
                    xmlwriter_start_attribute($xw, $attr);
                    xmlwriter_text($xw, $attr_val);
                    xmlwriter_end_attribute($xw);
                }
            }
            /****************************** Code End ******************************/


            if(isset($element['children'])){
                array_push($queue, 1); 
                $this->inLV('children');
                $level++;

                for($i = count($element['children']) - 1; $i >= 0; $i--)
                {
                    array_push($queue, $element['children'][$i]); 
                    array_push($this->childNum, $i);  
                }
            }
            else
            {
                $this->outLV();
                xmlwriter_end_element($xw);
            }
        }
        
        $final_file = xmlwriter_output_memory($xw);

        return response()->streamDownload(function () use($final_file)
        {
            echo $final_file;
        }, 'input.xml');


        //dd(xmlwriter_output_memory($xw)) ;
    }



    public function inLV($str){
        if(isset($str))
            $this->currentID = $this->currentID . '.' . $str; 
    }

    public function outLV(){
        $tokens = explode('.', $this->currentID);    
        if (count($tokens) === 1) return;
        array_pop($tokens);                   
        $this->currentID = implode('.', $tokens);
    }

}
