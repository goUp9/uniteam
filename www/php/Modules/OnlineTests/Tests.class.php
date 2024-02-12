<?php
namespace Modules\OnlineTests;
class Tests {
    
    public function get_tests(){
        $files=scandir($_SERVER['DOCUMENT_ROOT'].'/data/onlinetests/');        
        for ($i=0; $i<count($files); $i++){            
            if($files[$i]=='.' || $files[$i]=='..'){                
                unset($files[$i]);
            }            
        }
        sort($files);
        $tests=array();
        for($i=0; $i<count($files); $i++){
            if(strpos($files[$i], '.php')===FALSE){
                $tests[$i]['link']=str_replace('.xml', '', $files[$i]);
                $tests[$i]['title']=  str_replace('_',' ',str_replace('.xml', '', $files[$i]));
                $tests[$i]['descr']=$this->get_test_desctioption($tests[$i]['link']);
                $tests[$i]['numQuestions']=$this->get_num_questions($tests[$i]['link']);
            }
        }
        return $tests;
    }
    
    private function get_test($testName){
        $Test=new TestBase();
        $Test->set_xmlFilePath($_SERVER['DOCUMENT_ROOT'].'/data/onlinetests/'.$testName.'.xml');
        $test=$Test->set_testData()->get_testData();  
        return $test;
    }
    
    public function get_test_desctioption($testName){
        $test=$this->get_test($testName);         
        return (string)$test->description;
    }
    
    public function get_num_questions($testName){
        $test=$this->get_test($testName);
        $i=0;
        foreach($test->pages->page as $page){            
            $i++;
        }
        return $i;
    }
    
}

?>
