<?php
namespace Modules\Users;
class Admin extends \Modules\Modules{
    use \Modules\AdminPanel\Editing;
    use \Modules\Data;
    
    public $repository="Users";
    
    function listing($page){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->compile_assets();
        
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin_edit_user'),'link_base');  
        $this->Kernel->Content->set_data($this->Kernel->Content->insert_asset('link', 'admin_edit_users'),'page_link_base'); 
        
        $this->Kernel->Content->set_data('user', 'delLink'); // ajax delete item link
        
        
        /* new article action */        
        if(!empty($this->Kernel->Request->post)){
              $this->create();
        }
        
        /* get articles */
        $DataMngr=new \Modules\DataManager($this->entityManager);
        $usersData=$DataMngr->get_items_per_page($this->repository, 6, $page);
        $users=$usersData['data'];  
        foreach($users as &$user){
            $user['title']=$user['email'].' | '.$user['idUser'];
        }
        $this->Kernel->Content->set_data($users, 'data');
        
        $pages=$this->set_pagination_pages($usersData['totalPages'], $page);        
        $this->Kernel->Content->set_data($pages, 'pages');
        
        $FormF=new \Core\FormsFactory($this->entityManager);
        $form=$FormF->generate_form($this->repository,'users', CURRENT_PAGE);
        $this->Kernel->Content->set_form($form, 'newItem');
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function edit_user($id){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->entityManager);
        $Unlogged->unlogged();
        
        $this->Kernel->compile_assets();
                
        /* edit article action */
        $this->Kernel->Request=new \Core\Request();
        if(!empty($this->Kernel->Request->post)){                       
            $this->edit($this->Kernel->Request);
        }
        
        /* get user */
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $user=$DataMngr->get_item_by_id($this->repository, $id);
        
      
          
        $FormF=new \Core\FormsFactory($this->Kernel->entityManager);
        $form=$FormF->generate_form($this->repository,'users', CURRENT_PAGE,$user);
        $this->Kernel->Content->set_form($form, 'form');
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;     
    }
}

?>
