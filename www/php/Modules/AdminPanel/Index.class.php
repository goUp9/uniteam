<?php
namespace Modules\AdminPanel;
class Index extends \Modules\Modules{
    
    const SECTIONS_PATH='php/Bundle/Controllers/Admin/sections.json';
    
    function main(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        /* get sections*/
        $sections=\Core\Utils::read_json(self::SECTIONS_PATH);
        $this->Kernel->Content->set_data($sections, 'sections');

        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
                
    }
    
    function section($title){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->Content->set_data($title, 'title');
        
        /* get actions*/
        $sections=\Core\Utils::read_json(self::SECTIONS_PATH);
        $actions=$sections[str_replace("_", " ", $title)];
        if(count($actions)>1){
            foreach($actions as &$action){
                $action['link']=$this->Kernel->Content->insert_asset('link', $action['link']);
            }
            $this->Kernel->Content->set_data($actions, 'actions');
            $this->Kernel->Response->pathToTemplate='/templates/admin_mod/';
        }
        else {
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link', $actions[0]['link'])['href']);
        }
         
        return $this->Kernel;
        
    }
}

?>
