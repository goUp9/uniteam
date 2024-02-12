<?php
namespace Modules\PicturesManager;
abstract class Definitions {
    public $repository='Bundle\Doctrine\Entities\Pictures';
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
}

?>
