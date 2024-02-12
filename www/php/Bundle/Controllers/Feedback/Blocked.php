<?php
namespace Bundle\Controllers\Feedback;
class Blocked extends \Modules\Users\Profile {
    function main(){
        $this->unlogged();
        
        return $this->Kernel;
    }
}
