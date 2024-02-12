<?php
namespace Modules\TextsManager;
abstract class Definitions {
    public $repository="Bundle\Doctrine\Entities\Texts";
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    
    
}

?>
