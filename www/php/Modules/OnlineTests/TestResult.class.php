<?php
namespace Modules\OnlineTests;
class TestResult {
    
    private $testData;
    private $xmlFilePath;
    private $testName;
    
    public function set_xmlFilePath($path){
        $this->xmlFilePath=$path;
        return $this;
    }
    
    public function set_testName($name){
        $this->testName=$name;
        return $this;
    }
    
    public function get_result(){
        $Request=new \Core\Request();        
        $clean=  str_replace('&#34;', '"', $Request->get['result']);
        $resultPost=  json_decode($clean);
        if(is_file($this->xmlFilePath.'/'.$this->testName.'.php')){
            include_once $this->xmlFilePath.'/'.$this->testName.'.php';
            $countedRes=count_result($resultPost);
        }
        $this->testData=  simplexml_load_file($this->xmlFilePath.'/'.$this->testName.'.xml');        
        foreach($this->testData->results->result as $result){            
            $res=(string)$result['value'];
            if($countedRes===$res){                
                $type=(string)$result;
            }
        }
        return $type;
    }
}

?>
