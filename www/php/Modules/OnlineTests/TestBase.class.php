<?php
namespace Modules\OnlineTests;
class TestBase {
    
    private $testData;
    private $xmlFilePath;
    
    public function set_xmlFilePath($path){
        $this->xmlFilePath=$path;
    }

    public function set_testData(){
        $this->testData=  simplexml_load_file($this->xmlFilePath);
        return $this;
    }
    
    public function get_testData(){
        return $this->testData;
    }
}
