<?php
namespace Modules\TextsManager;
class GeneralTexts extends Definitions{
    use \Modules\Data;
    use \Modules\AdminPanel\Editing;
    
    public function general_texts(){
         $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->compile_assets();        
          
        /* create delete item link */
        $this->Kernel->Content->set_data('general-texts-manager', 'delLink');
             
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function get_selects(){
        $handle=fopen('php/Modules/TextsManager/data/general_texts.json', 'r');
        echo fread($handle, filesize('php/Modules/TextsManager/data/general_texts.json'));
    }
    
    function ajax_get_texts($page){
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $texts=$DataMngr->get_items_per_page($this->Kernel->Request->post['repo'], 10, $page);
        echo json_encode($texts);
    }
    
    function ajax_generate_new_item_form(){        
        $form=$this->Kernel->FormsF->generate_default_table($this->Kernel->Request->post['repo'],'text', NULL,NULL,'gt');
        echo $form;
    }
    
    function delete(){
         $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();        
        
        if(isset($this->Kernel->Request->post['id'])){
            $DataMngr=new \Modules\DataManager($this->Kernel);
            $DataMngr->delete_item_by_id($this->Kernel->Request->post['repo'],$this->Kernel->Request->post['id']);
        }
    }
    
    function ajax_form_action(){
         $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        
        $DataMngr=new \Modules\DataManager($this->Kernel);    
        if(isset($this->Kernel->Request->post['form']['id'])&&!empty($this->Kernel->Request->post['form']['id'])){
            $this->modify_item($DataMngr);
        }
        else {
            $this->add_item($DataMngr);
        }        
    }
    
    private function add_item(\Modules\DataManager $DataMngr){
        $DataMngr->create_new_item($this->Kernel->Request->post['repo'], $this->Kernel->Request->post['form']);
    }
    
    private function modify_item(\Modules\DataManager $DataMngr){
        $DataMngr->update_item_by_id($this->Kernel->Request->post['form']['id'], $this->Kernel->Request->post['repo'], $this->Kernel->Request->post['form']);
    }
}

?>
