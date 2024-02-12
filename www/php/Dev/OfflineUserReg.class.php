<?php
namespace Dev;
class OfflineUserReg {
    
    private $repository="";
    private $fieldsToValidate="";
    
    public function __construct($entityManager) {
        $this->entityManager=$entityManager;
    }
    
    public function set_repository($repository){
        $this->repository=$repository;
    }
    
    public function set_fieldsToValidate($fields){
        $this->fieldsToValidate=$fields;
    }
    
    public function register_user(){
        $Kernel=new \Core\Kernel($this->entityManager);
        $Registration=new \Modules\Users\Registration($Kernel);
        $Registration->repository=  $this->repository; 
        if(!$Registration->validate_user_exists($this->fieldsToValidate)){// check if the user already exists          
            $Registration->register();
        }
        else{
            echo "User exists!";
        }
    }
}

?>
