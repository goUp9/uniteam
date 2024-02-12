<?php
namespace Bundle\Controllers\Payments;
class Escrowed extends \Modules\Modules{
    public $repository=array("UINQuery","Contacts");
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;        
    }
    
    public function main($idQuery){
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        if(is_object($UINQuery)){
            $UINQuery->setStatus('Escrowed');
            $UINQuery->setIsAwaitingEscrow(FALSE);
            $UINQuery->setDateEscrowed();
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();
            
            #send notifications
            $this->set_escrowed_notification($UINQuery);
            
            #add contact
            $this->add_contacts($UINQuery);
            
            $this->set_payment_reminder($UINQuery);
            $this->set_feedback($UINQuery);
        }
        return $this->Kernel;
    }
    
    private function set_escrowed_notification($UINQuery){

        $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->get_chosenSupplier(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_FUNDS_ESCROWED);
        $Notification->send();
        #email        
        $replacementArray['username']=$UINQuery->get_chosenSupplier()->getUsername();
        $ENotifications=new \Modules\UserNotifications\Email($UINQuery->get_chosenSupplier(), 'Funds escrowed on uinteam', 'funds_escrowed.html.twig', $replacementArray);
        $ENotifications->send(); 
  
        $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->getUser(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_REASSURE_ASKER);
        $Notification->send();
        #email
        $replacementArray['queryData']['id']=$UINQuery->getId();
        $replacementArray['username']=$UINQuery->getUser()->getUsername();
        $ENotifications=new \Modules\UserNotifications\Email($UINQuery->getUser(), 'Supplier confirmation for your Query #'.$UINQuery->getId().' on uinteam', 'confirmation_for_asker.html.twig', $replacementArray);
        $ENotifications->send(); 
    }
    
    private function add_contacts($UINQuery){
        $Contacts = $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneBy(['idUser'=>$UINQuery->getUser()->getId(),'idContact'=>$UINQuery->get_chosenSupplier()->getId()]);
        if(!is_object($Contacts)){
            $Contacts=new \Bundle\Doctrine\Entities\Contacts();
            $Contacts->setIdUser($UINQuery->get_chosenSupplier());
            $Contacts->setIdContact($UINQuery->getUser());
            $this->Kernel->entityManager->persist($Contacts);
            $this->Kernel->entityManager->flush();
        }
        
        $Contacts = $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneBy(['idUser'=>$UINQuery->get_chosenSupplier()->getId(),'idContact'=>$UINQuery->getUser()->getId()]);
        if(!is_object($Contacts)){
            $Contacts=new \Bundle\Doctrine\Entities\Contacts();
            $Contacts->setIdUser($UINQuery->getUser());
            $Contacts->setIdContact($UINQuery->get_chosenSupplier());
            $this->Kernel->entityManager->persist($Contacts);
            $this->Kernel->entityManager->flush();
        }
    }
    
    private function set_payment_reminder($UINQuery){
        // CRON
        $CrontabMngr=new \CrontabManager\CrontabManager();
        $Job=$CrontabMngr->newJob();
        
        $timezone_offset=  abs(\Core\Utils::get_timezone_difference());
        
        if($UINQuery->getWhens()->toArray()[0]->getDate2()===NULL){
            $minutes=date( "i", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset+60*60*24); // 86400=24 hours
            $hours=date( "H", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset+60*60*24);
            $days=date( "d", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset+60*60*24);
            $month=date( "m", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset+60*60*24);
        }
        else {
            $minutes=date( "i", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset+60*60*24);
            $hours=date( "H", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset+60*60*24);
            $days=date( "d", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset+60*60*24);
            $month=date( "m", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset+60*60*24);
        }
        

        $Job->on($minutes.' '.$hours.' '.$days.' '.$month.' *');

        $Job->doJob('wget -q -O temp.txt  http://'.$_SERVER['HTTP_HOST'].'/admin-payment-reminder/'.$UINQuery->getId().'/');

        
        $CrontabMngr->add($Job);
        
        $CrontabMngr->save();
        $CrontabMngr->cleanManager();
    }
    
    private function set_feedback($UINQuery){
        // CRON
        $CrontabMngr=new \CrontabManager\CrontabManager();
        $Job=$CrontabMngr->newJob();
        
        $timezone_offset=  abs(\Core\Utils::get_timezone_difference());
        
        if($UINQuery->getWhens()->toArray()[0]->getDate2()===NULL){
            $minutes=date( "i", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset); 
            $hours=date( "H", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset);
            $days=date( "d", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset);
            $month=date( "m", $UINQuery->getWhens()->toArray()[0]->getDate1()->getTimestamp()-$timezone_offset);
        }
        else {
            $minutes=date( "i", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset);
            $hours=date( "H", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset);
            $days=date( "d", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset);
            $month=date( "m", $UINQuery->getWhens()->toArray()[0]->getDate2()->getTimestamp()-$timezone_offset);
        }
        

        $Job->on($minutes.' '.$hours.' '.$days.' '.$month.' *');

        $Job->doJob('wget -q -O temp.txt  http://'.$_SERVER['HTTP_HOST'].'/send-feedback-notification/'.$UINQuery->getId().'/'.$UINQuery->get_chosenSupplier()->getId().'/');

        
        $CrontabMngr->add($Job);
        
        $CrontabMngr->save();
        $CrontabMngr->cleanManager();
    }
    
}
