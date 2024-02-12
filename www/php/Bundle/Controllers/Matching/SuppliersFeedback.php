<?php
namespace Bundle\Controllers\Matching;
class SuppliersFeedback extends \Modules\Users\Profile{
    public $repository=array("UINQuery", "Users","SuppliersFeedback");
    
    function main(){
        $this->unlogged();
        if(isset($this->Kernel->Request->post['idSupplier'])&&isset($this->Kernel->Request->post['idAskQuery'])){            
            $SQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(['idUser'=>$this->Kernel->Request->post['idSupplier'],'type'=>'supply']);
            
            if(is_object($SQuery)){
                $SuppliersFeedback=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneBy(['AskQuery'=>$this->Kernel->Request->post['idAskQuery']]);
                $AQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($this->Kernel->Request->post['idAskQuery']);
                if(is_object($SuppliersFeedback)){
                    $this->Kernel->entityManager->remove($SuppliersFeedback);
                    $this->Kernel->entityManager->flush();
                }
                $SuppliersFeedback=new \Bundle\Doctrine\Entities\SuppliersFeedback();
                $SuppliersFeedback->setFeedback($this->Kernel->Request->post['feedback']);
                $this->Kernel->entityManager->persist($SuppliersFeedback);
                $AQuery->setLeftFeedback($SuppliersFeedback);                
                $this->Kernel->entityManager->flush();
                $SQuery->setSuppliersFeedback($SuppliersFeedback);
                $this->Kernel->entityManager->persist($SQuery);
                $this->Kernel->entityManager->flush();
                
                #if 0 feedback
                if($this->Kernel->Request->post['feedback']==0){
                    $this->send_service_zero_rated_notification($this->Kernel->Request->post['idAskQuery']);
                }                
                $AQuery->getUser()->setFeedbackBlocked(FALSE);
                $AQuery->archive();
                $this->Kernel->entityManager->persist($AQuery);
                $this->Kernel->entityManager->flush();
            }
        }
        return $this->Kernel;
    }
    
    function send_notification($idQuery,$idSupplier){
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        
        $Query->getUser()->setFeedbackBlocked(TRUE);
        $this->Kernel->entityManager->persist($Query);
        $this->Kernel->entityManager->flush($Query);
        
        $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $Query->getUser(), $Query, \Modules\UserNotifications\Notification::TYPE_GIVE_FEEDBACK);
        $Notification->send();
        #email
        $replacementArray['queryData']['id']=$Query->getId();
        $replacementArray['username']=$Query->getUser()->getUsername();
        $ENotifications=new \Modules\UserNotifications\Email($Query->getUser(), 'Please leave feedback for your Query #'.$Query->getId().' on uinteam', 'give_feedback.html.twig', $replacementArray);
        $ENotifications->send(); 
        return $this->Kernel;
    }
    
    function send_service_zero_rated_notification($idQuery){
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        
        $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $Query->get_chosenSupplier(), $Query, \Modules\UserNotifications\Notification::TYPE_ZERO_FEEDBACK);
        $Notification->send();
        #email
        $replacementArray['username']=$Query->get_chosenSupplier()->getUsername();
        $ENotifications=new \Modules\UserNotifications\Email($Query->get_chosenSupplier(), 'You have recieved a 0 feedback on uinteam', 'zero_feedback.html.twig', $replacementArray);
        $ENotifications->send(); 
        return $this->Kernel;
    }
    
    function edit(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $SuppliersFeedback=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($this->Kernel->Request->post['id']);
        if(is_object($SuppliersFeedback)){
            $SuppliersFeedback->setFeedback($this->Kernel->Request->post['val']);
            $this->Kernel->entityManager->persist($SuppliersFeedback);
            $this->Kernel->entityManager->flush();
        }
        else {
            echo 'false';
        }
    }
    
    function delete(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $DataMngr->delete_item_by_id($this->get_repository()[2], $this->Kernel->Request->post['id']);
    }
    
}
