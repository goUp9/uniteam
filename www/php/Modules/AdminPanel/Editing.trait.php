<?php
namespace Modules\AdminPanel;
trait Editing{
    
    function delete(){
        $callback=array($this,'delete_callback');
        if(is_callable($callback)){
            call_user_func($callback);
        }
        
        if(isset($this->Kernel->Request->post['id'])){
            $DataMngr=new \Modules\DataManager($this->Kernel);
            $DataMngr->delete_item_by_id($this->get_repository()[0],$this->Kernel->Request->post['id']);
        }
    }
    
    function edit(){
        
        $callback=array($this,'edit_callback');
        
        if(is_callable($callback)){
            call_user_func($callback,$this->Kernel->Request);
        }
        
        $id=$this->Kernel->Request->post['id'];
        unset($this->Kernel->Request->post['id']);
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $entry=$DataMngr->update_item_by_id($id, $this->get_repository()[0], $this->Kernel->Request->post); 
        
        return $entry;
    }
    
    function create(){
        
        $callback=array($this,'create_callback');
        if(is_callable($callback)){
            call_user_func($callback,$this->Kernel->Request);
        }
        
        $DataMngr=new \Modules\DataManager($this->Kernel);
        return $DataMngr->create_new_item($this->get_repository()[0], $this->Kernel->Request->post);        
    }
    
}

?>
