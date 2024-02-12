<?php
namespace Dev\Exceptions;
class SystemException extends ExceptionFactory{
    
    function kill_process(){
        $this->fatal_error();
    }
    
}

?>
