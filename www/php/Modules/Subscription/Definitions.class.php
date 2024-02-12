<?php
namespace Modules\Subscription;
abstract class Definitions {
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }        
}

?>
