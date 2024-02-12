<?php
namespace Modules\AdminPanel;

class Login extends \Modules\Users\Login{
    public $repository="AdminUsers";
    
    public $usernameFields=array('username');
    public $userSessionName='admin_user';
    
    function main(){        
        $LoginForm=new LoginForm($this->Kernel, 'form__login');        
        $this->Kernel->Content->set_form($LoginForm->form,'login');
        
        if(!empty($this->Kernel->Request->post)){
            $this->action_login();
        }
        
        if($this->is_logged()){
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','admin__home')['href']);
        }
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function action_login(){
        $this->login();
    }
    
    function action_logout(){                
        $this->logout(); 
        header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','admin__login')['href']);
    }
    
    function unlogged(){
        if(!$this->is_logged()){
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','admin__login')['href']);
        }
    }
}

?>
