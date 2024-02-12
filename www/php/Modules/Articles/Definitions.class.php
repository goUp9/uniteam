<?php
namespace Modules\Articles;
abstract class Definitions {
    public $repository='Bundle\Doctrine\Entities\Articles';
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
}

?>
