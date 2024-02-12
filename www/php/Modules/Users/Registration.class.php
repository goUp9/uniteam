<?php
namespace Modules\Users;
class Registration extends \Modules\Modules {
    public $repository="users";
    
    public static function generate_password($password){
        // A higher "cost" is more secure but consumes more processing power
        $cost = 10;

        // Create a random salt
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        // Prefix information about the hash so PHP knows how to verify it later.
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        // Hash the password with the salt
        $hash = crypt($password, $salt);
        
        $data=array('hash'=>$hash,'salt'=>$salt);
        return $data;
    }
    
    /* if user already exists */
    public function validate_user_exists($fields){        
        $flag=FALSE;
        $Validation=new Validation($this->Kernel);
        $fieldsExist=array();
        foreach($fields as $field){            
            if($Validation->form_field_exists($field)){                
                $entry = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array($field=>$this->Kernel->Request->post[$field]));
                if(is_object($entry)){
                    array_push($fieldsExist, $field);
                }                
            }
            else {
                $fieldsExist=FALSE;
            }
        }
        return $fieldsExist;
    }
    
//    public function ajax_user_exists(){        
//        $entry = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array($this->Kernel->Request->post['field']=>$this->Kernel->Request->post['value']));
//        if(is_object($entry)){
//            if(\Core\Utils::is_ajax()){
//                echo TRUE;
//            }
//            else {
//                return TRUE;
//            }
//        }
//        else {
//            if(\Core\Utils::is_ajax()){
//                echo FALSE;
//            }
//            else {
//                return FALSE;
//            }
//        }
//    }
    
    public function register(){
        $DataMngr=new \Modules\DataManager($this->Kernel);
        return $DataMngr->create_new_item($this->get_repository()[0], $this->Kernel->Request->post);         
    }
    
    protected function varify_email(){        
        if(!empty($this->Kernel->Request->get)){            
            $key=$this->Kernel->Request->get['key'];            
            $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array("varificationEmail"=>$key)); 
            if(is_object($entry)){
                $entry->setStatus(TRUE);
                $entry->setVarificationEmail(NULL);
                $this->Kernel->entityManager->persist($entry);
                $this->Kernel->entityManager->flush();
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }
}

?>
