<?php
namespace Bundle\Controllers\Users;
class PasswordRestore extends \Modules\Users\PasswordRestore{
    public $repository="Users";
    
    function restoring(){
        $Login=new \Modules\Users\Login($this->Kernel);
        $Commons=new \Bundle\Controllers\Commons\Users($this->Kernel);
        $Commons->remember_user();
        if($Login->is_logged()){
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','myuin__personal')['href']);
        }
        $Form=new RestorePasswordForm($this->Kernel, 'form__restore_password', '');
        $this->Kernel->Content->set_form($Form->form,'restore_password');
        return $this->Kernel;
    }
    
    function action_request(){
        if(\Core\Utils::is_ajax()){
            $result=$this->set_random_password();
            if ($result!=FALSE){
                $Mailer=new \PHPMailer();
                new \Modules\EmailNotifications\EmailNotification($this->Kernel,$Mailer,$this->Kernel->Request->post['email'], 1,$result, TRUE);
            }
            else {
                echo 0;
            }
        }
    }
    
    function reseting(){
        $Profile=new \Bundle\Controllers\MyUin\Personal($this->Kernel);
        $Profile->unlogged();
        
        $Form=new ResetPasswordForm($this->Kernel, 'form__reset_password', '');
        $this->Kernel->Content->set_form($Form->form,'reset_password');
        return $this->Kernel;
    }
    
    
    function action_set_password(){        
        if(\Core\Utils::is_ajax()){
            if(!empty($this->Kernel->Request->post)){                
                $result=$this->set_new_password(new Login($this->Kernel), new Users($this->Kernel)); 
                if($result===0){
                    $AjaxResult=new \Core\AjaxResult(TRUE, "Password has been changed successfully");
                }
                else if ($result===1){
                    $AjaxResult=new \Core\AjaxResult(FALSE, "New passwords don't match");
                }
                else if ($result===2){
                    $AjaxResult=new \Core\AjaxResult(FALSE, "Current password is incorrect");
                }
                echo $AjaxResult->to_JSON();
            }
        }
    }
    
}
