<?php
namespace Modules\TextsManager;
class DefaultTexts extends \Modules\Modules{    
    use \Modules\Data;
    use \Modules\AdminPanel\Editing;
    
        public $repository=array("TextManager");


//    public function actions(){
//         $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
//        $Unlogged->unlogged();
//        
//        $this->Kernel->compile_assets();
//        
//        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
//        return $this->Kernel;
//
//    }
    
    public function all_texts($page){
         $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
//        $this->Kernel->compile_assets();
        
        if(isset($this->Kernel->Request->post)&&!empty($this->Kernel->Request->post)){
            $this->create();
        }        
        
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin__default_text_manager_edit'),'link_base');  
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin__default_text_manager'),'page_link_base'); 
        
        /* create delete item link */
        $this->Kernel->Content->set_data('default-texts-manager', 'delLink');
        
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $texts=$DataMngr->get_items_per_page($this->get_repository()[0], 10, $page);   
//        foreach($texts['data']as &$text){
//            $title=str_split($text['text'], 100);
//            $text['title']=$title[0].'(...)';
//        }
        $this->Kernel->Content->set_data($texts['data'], 'data');
        
        $pages=$this->set_pagination_pages($texts['totalPages'], $page);
        $this->Kernel->Content->set_data($pages, 'pages');   
        
        $TextsMngrForm=new \Modules\TextsManager\Forms();
        $this->Kernel->Content->set_form($TextsMngrForm->text(), 'newItem');        
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
        
    }
    
   public function edit_text($id){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        if(isset($this->Kernel->Request->post)&&!empty($this->Kernel->Request->post)){
            $this->edit();
        }        

        $DataMngr=new \Modules\DataManager($this->Kernel);     
        $text=$DataMngr->get_item_by_id($this->get_repository()[0], $id); 
       
          
        $TextsMngrForm=new \Modules\TextsManager\Forms();
//        $TextsMngrForm->set_formName('form--text');
        $this->Kernel->Content->set_form($TextsMngrForm->text($text), 'form'); 
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;       
    }
    
    public function compile_texts($textsTitles=NULL){
        if($textsTitles!==NULL){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('tm', 'tm.title,tm.text')
            ->from($this->get_repository()[0], 'tm');
            foreach($textsTitles as $title){
                $QB->orWhere("tm.title ='".$title."'");
            }
            $texts= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }
        else {
            $DataMngr=new \Modules\DataManager($this->Kernel);     
            $texts=$DataMngr->get_items($this->get_repository()[0]);
        }
        $tM=[];
        foreach($texts as $text){
            $tM[$text['title']]=$text['text'];
        }        
        $this->Kernel->Content->set_data($tM,"textManager");
        
    }
    
}

?>
