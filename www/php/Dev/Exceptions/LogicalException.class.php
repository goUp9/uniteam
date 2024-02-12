<?php
namespace Dev\Exceptions;
class LogicalException extends ExceptionFactory {
    
    #directory already exists (mkdir exception)
    function directory_exists($dir){
        if(\Core\Utils::is_empty_dir($dir)){
            rmdir($dir);
        }
        else {
            $this->fatal_error();
        }
    }
    
}

?>
