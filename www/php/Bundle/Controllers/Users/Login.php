<?php
namespace Bundle\Controllers\Users;
class Login extends \Modules\Users\Login{
    public $repository=array("Users","UINQuery");
    
    public $usernameFields=array('username');
    
    public function login(){
        $user=$this->check_username();                  
            if($user!==FALSE){
                if($user->getStatus()===TRUE){  
                    $passwordFlag=$this->check_password($user); 
                    if($passwordFlag){ 
                        $this->set_session($user);
                        if(\Core\Utils::is_ajax()){
                            echo '1'; //for ajax
                            if(isset($_SESSION['currentQuery'])&&!empty($_SESSION['currentQuery'])){
                                $this->set_query_for_new_user($user);
                            }
                        }
                        return $user;
                    }
                    else {
                        return "password is incorrect";
                    }
            }
            else {
                return "user is deactivated"; 
            }            
        }
        else {
            return "user doesn't exist";
        }
    }
    
    private function set_query_for_new_user($UserEntry){
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($_SESSION['currentQuery']);
        if(is_object($Query)){
            $Query->setIdUser($UserEntry);
            $this->Kernel->entityManager->persist($Query);
            $this->Kernel->entityManager->flush();
            unset($_SESSION['currentQuery']);
        }
    }
    
    function action_login(){
        if(\Core\Utils::is_ajax()){ // if user is not activated on the admin panel
            $login=$this->login();
            if(is_object($login)){
                $this->remember_me('remember_me', $login);
                if($login->getNewsletterSubscribed()===NULL){
                    $_SESSION['promtNewsletter']=TRUE;
                }
            }
            else {
                echo $login;
            }
            
        }
    }
    
    function action_logout(){
        session_unset();
        $this->user_forget('remember_me');        
        $this->logout();        
    }
    
    function remembered(){
            $this->user_remembered('remember_me');       
    }    
    
}
