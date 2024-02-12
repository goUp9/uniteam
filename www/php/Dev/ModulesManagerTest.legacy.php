<?php
//require_once 'ModulesManager.class.php';
class ModulesManagerTest extends PHPUnit_Framework_TestCase{
    public function testadd_mod_folder_to_modules(){        
        $ModManager=new \Dev\ModulesManager();
        $folder_name="AdminPanel";
        $expected=true;
        $actual=$ModManager->add_mod_folder_to_modules($folder_name);
        $this->assertEquals($expected,$actual);
    }
}

?>
