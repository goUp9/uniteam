<?php
class ModulesTest extends PHPUnit_Framework_TestCase{
    
    public function testget_repository(){
        require '/config/doctrine_bootstrap.php';
        $Kernel=new \Core\Kernel($entityManager);
        $stub = $this->getMockForAbstractClass('\Modules\Modules',array($Kernel));
        $stub->repository="TestRepo";
        $this->expectOutputString(DOCTRINE_ENTITIES_PATH."TestRepo");
        echo $stub->get_repository();
    }
    
}
