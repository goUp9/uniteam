<?php
namespace Modules\Users;
class PasswordRestore extends \Modules\Modules{    
    public $repository="users";
    
    /*
     * RESULTS
     */
    const SUCCESS= 0;
    const PSSWRDS_DONT_MATCH=1;
    const CURR_PSSWRD_INCORR=2;
    
    protected function set_random_password(){
        $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array("email"=>$this->Kernel->Request->post['email']));
        if(is_object($entry)){
            $newPassword= \Core\Utils::generate_random_string(15);
            $entry->setPassword($newPassword);
            $data['id']=$entry->getId();
            $data['new_password']=$newPassword;
            $this->Kernel->entityManager->persist($entry);            
            $this->Kernel->entityManager->flush();            
            return $data;
        }
        else {            
            return FALSE; //user not found
        }
    }
    
    protected function set_new_password(\Bundle\Controllers\Users\Login $Login, \Bundle\Controllers\Users\Users $Users){
        $entry=$Users->get_current_user();         
        if($Login->check_password($entry)){             
            if($this->Kernel->Request->post['newpassword']===$this->Kernel->Request->post['newpassword2']){                
                $entry->setPassword($this->Kernel->Request->post['newpassword']);
                $this->Kernel->entityManager->persist($entry);
                $this->Kernel->entityManager->flush();
                return self::SUCCESS; // success
            }
            else {
                return self::PSSWRDS_DONT_MATCH; // new passwords don't match
            }
        }
        else {
            return self::CURR_PSSWRD_INCORR; // current password incorrect
        }
    }
    
    
}
