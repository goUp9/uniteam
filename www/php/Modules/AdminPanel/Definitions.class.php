<?php
namespace Modules\AdminPanel;
abstract class Definitions {
    public $repository="Bundle\Doctrine\Entities\AdminUsers";
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
}

?>
