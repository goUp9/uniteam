<?php
namespace Modules\Users;
class Login extends \Modules\Modules{
    public $repository="Users";
    
    public $usernameFields=array('email');
    public $userSessionName='user';
    
    public function logout(){
        unset($_SESSION[$this->userSessionName]);
    }
    
    public function is_logged(){
            if(isset($_SESSION[$this->userSessionName])&& !empty($_SESSION[$this->userSessionName])){
                return TRUE;
            }
            else{
                return FALSE;
            }
    }
    
    public function ajax_is_logged(){
        if(isset($_SESSION[$this->userSessionName])&& !empty($_SESSION[$this->userSessionName])){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    
    public function login(){
        $user=$this->check_username();
        if($user!==FALSE){            
            $passwordFlag=$this->check_password($user);            
            if($passwordFlag){ 
                $this->set_session($user);
                if(\Core\Utils::is_ajax()){
                    echo '1'; //for ajax
                }
                return $user;
            }
            else {
                return "password is incorrect";
            }
        }
        else {
            return "user doesn't exist";
        }
    }
    
    public function remember_me($fieldName, $entry){        
        if($this->Kernel->Request->post[$fieldName]=='true'){
            $year = time() + 31536000;           
            setcookie($fieldName, $entry->getId(), $year,'/');
        }
        else {
            if(isset($_COOKIE[$fieldName])){
                unset($_COOKIE[$fieldName]);
                setcookie($fieldName, null, -1, '/');
            }
        }
    }
    
    public function ajax_login(){
        $user=$this->check_username();
        if($user!==FALSE){
            $passwordFlag=$this->check_password($user);
            if($passwordFlag){
                $this->set_session($user);
                echo "Login successful";
            }
            else {
                echo "password is incorrect";
            }
        }
        else {
            echo "user doesn't exist";
        }
    }
    
    public function check_username(){
        $username=$this->Kernel->Request->post['username'];
        foreach ($this->usernameFields as $field){
            $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array($field=>$username));
            if(is_object($entry)){
                $user=$entry;
            }
        }
        if(isset($user)){
            return $user;
        }
        else {
            return FALSE;
        }
        
    }
    
    public function check_password($entry){
        $password=$this->Kernel->Request->post['password'];
        $hash=$entry->getPassword();
        
        if ($hash===crypt($password, $hash)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
    public function user_remembered($cookieName){        
        if(isset($_COOKIE[$cookieName])){
            $this->Kernel->Session->set(array('id'=>$_COOKIE[$cookieName]),$this->userSessionName);
        }
    }
    
    public function user_forget($cookieName){ 
        if(isset($this->Kernel->Session->access->user)){
            if(isset($_COOKIE[$cookieName])){                
                unset($_COOKIE[$cookieName]);
                setcookie($cookieName, null, -1, '/');
            }
        }
    }
    
    protected function set_session($entry){
        $_SESSION[$this->userSessionName]['id']=$entry->getId();        
    }
    
    
}

?>
