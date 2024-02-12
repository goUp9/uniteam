<?php
namespace Bundle\Controllers\Users;
class Users extends \Modules\Users\Users{
    public $repository="Users";
    
    public function newsletter_prompted(){        
        if(\Core\Utils::is_ajax()){
            if(isset($this->Kernel->Request->post['answer'])){                
                $User=$this->get_current_user();
                if($this->Kernel->Request->post['answer']==='true'){
                    $User->setNewsletterSubscribed(TRUE);
                }
                else{
                    $User->setNewsletterSubscribed(FALSE);
                }
                $this->Kernel->entityManager->persist($User);
                $this->Kernel->entityManager->flush();
                unset($_SESSION['promtNewsletter']);
            }
        }
    }
}
