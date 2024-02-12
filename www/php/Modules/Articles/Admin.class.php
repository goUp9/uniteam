<?php
namespace Modules\Articles;
class Admin extends Definitions{
    use \Modules\AdminPanel\Editing;
    use \Modules\Data; 
        
    function articles_list($page){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->compile_assets();
        
        $this->Kernel->Meta->setTitle('Edit Articles');
        
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin__edit_article'),'link_base');  
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin__edit_articles'),'page_link_base'); 
        
        /* create delete item link */
        $this->Kernel->Content->set_data('article', 'delLink');
        
        
        if(!empty($this->Kernel->Request->post)){
              $this->create();
        }
        
        /* get articles */
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $articlesData=$DataMngr->get_items_per_page($this->repository, 6, $page);
        $articles=$articlesData['data'];
        $this->Kernel->Content->set_data($articles, 'data');
        
        $pages=$this->set_pagination_pages($articlesData['totalPages'], $page);
        $this->Kernel->Content->set_data($pages, 'pages');
        
        $form=$this->Kernel->FormsF->generate_form($this->repository, 'form--article', CURRENT_PAGE);
        $this->Kernel->Content->set_form($form, 'newItem');
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function edit_article($id){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->compile_assets();
        
        $this->Kernel->Meta->setTitle('Edit Articles');
                
        /* edit article action */
        if(!empty($this->Kernel->Request->post)){
            $this->edit();
        }
        
        /* get article */
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $article=$DataMngr->get_item_by_id($this->repository, $id);        
          
        $form=$this->Kernel->FormsF->generate_form($this->repository, 'form--article', CURRENT_PAGE,$article);
        $this->Kernel->Content->set_form($form, 'form');
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;    
    }
}

?>
