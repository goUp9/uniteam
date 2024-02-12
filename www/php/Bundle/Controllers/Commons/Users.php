<?php
namespace Bundle\Controllers\Commons;
class Users extends \Modules\Modules{
    
    function remember_user(){
        $Login=new \Bundle\Controllers\Users\Login($this->Kernel);
        $Login->remembered();
        
        if($Login->is_logged()){
            $Users=new \Modules\Users\Users($this->Kernel);        
            $User=$Users->get_current_user();
            $username=$User->getUsername();
            
            $this->Kernel->Content->set_data($username,'current_username');
        }
    }
    
}
