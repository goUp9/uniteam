<?php
namespace Dev\Exceptions;
class ExceptionFactory extends \Exception{    
    
    function fatal_error(){
        $error['msg']=$this->getMessage();
        $error['file']=$this->getFile();
        $error['line']=$this->getLine();
        $error['trace']=$this->getTrace(); 
        $error['code']=$this->getCode();
        echo $error['msg'];
//        $Content->set_data($error, 'error');
          
               
//        $tpl='fatal_error.html.twig';
//        $Response=new \Core\Response(); 
//        $Response->set_pathToTemplate('/templates/dev/');
//        $Response->set_content($Content)
//                ->set_template($tpl)
//                ->set_meta($Meta)
//                ->render(); 
        die();
    }
    
}

?>
