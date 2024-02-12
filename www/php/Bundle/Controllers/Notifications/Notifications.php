<?php
namespace Bundle\Controllers\Notifications;
class Notifications extends \Modules\UserNotifications\Notifications{
    public $repository=["UserNotifications","UINQuery", "AdminPayments"];
    
    public function main(){
        $this->unlogged();
        
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        $VideosCtrl=new \Bundle\Controllers\MyUin\Videos($this->Kernel);
        $videos=$VideosCtrl->get_videos();
        $this->Kernel->Content->set_data($videos,'videos');
        
        return $this->Kernel;
    }
    
    public function supplier_selected($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $Settings = $this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById(1);
        $paymentSettings['account']=$Settings->getAccount();
        if($Settings->getIsLive()){
            $paymentSettings['isLive']=TRUE;
        }
        $this->Kernel->Content->set_data($paymentSettings,'paymentSettings');
        
        
        
        $QueryData=  $this->get_query_details($idQuery);        
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
       
        if($QueryData['QueryWhenAsker'][0]['date1']<new \DateTime('now', new \DateTimeZone('Europe/London'))){
            $this->Kernel->Content->set_data(TRUE,'pastDate');
        }
        else {
            $this->Kernel->Content->set_data(FALSE,'pastDate');
        }
               
             
        return $this->Kernel;
    }
    
    public function set_read($idNotification){
        $Notifications=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idNotification);
        if(!$Notifications->getIsRead()){
            $Notifications->setIsRead(TRUE);
            $this->Kernel->entityManager->persist($Notifications);
            $this->Kernel->entityManager->flush();
        }
    }
    
    public function set_unread($idNotification){
        $Notifications=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idNotification);
        if($Notifications->getIsRead()){
            $Notifications->setIsRead(FALSE);
            $this->Kernel->entityManager->persist($Notifications);
            $this->Kernel->entityManager->flush();
        }
    }
    
    public function new_asker_request($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        
        $alreadyAcceptedFlag=FALSE;
        foreach($QueryData['cSuppliers'] as $competingSupplier){
            if($competingSupplier['id']==$_SESSION['user']['id']){
                $alreadyAcceptedFlag=TRUE;
            }
        }
        
        $this->Kernel->Content->set_data($QueryData,'queryDetails');  
        $this->Kernel->Content->set_data($alreadyAcceptedFlag,'alreadyAcceptedFlag'); 
        
        return $this->Kernel;
    }
    
    public function funds_escrowed($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->getUser()->getUsername(),'asker');        
        
        return $this->Kernel;
    }
    
    public function supplier_paid($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->getUser()->getUsername(),'asker');        
        
        return $this->Kernel;
    }
    
    public function adviser_paid($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->getUser()->getUsername(),'asker');        
        
        return $this->Kernel;
    }
    
    public function give_feedback($idQuery,$idNotification){ 
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getId(),'idSupplier');
        
        
                
        return $this->Kernel;
    }
    
    public function zero_feedback($idQuery,$idNotification){ 
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->getUser()->getUsername(),'asker');
        
        
        
        return $this->Kernel;
    }
    
    public function confirmation_for_asker($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $QueryData=  $this->get_query_details($idQuery);
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $this->Kernel->Content->set_data($UINQuery->get_chosenSupplier()->getUsername(),'supplier');
        $this->Kernel->Content->set_data($UINQuery->getUser()->getUsername(),'asker');
            
        
        return $this->Kernel;
    }
    
    public function new_advice_request($idQuery,$idNotification){
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $UINQuery->check_isExpired();
        
        $QueryData=  $this->get_query_details($idQuery);
        $alreadyAcceptedFlag=FALSE;
        
        foreach($QueryData['cAdvisers'] as $competingAdviser){
            if($competingAdviser['id']==$_SESSION['user']['id']){
                $alreadyAcceptedFlag=TRUE;
            }
        }
        
        $this->Kernel->Content->set_data($QueryData,'queryDetails');  
        $this->Kernel->Content->set_data($alreadyAcceptedFlag,'alreadyAcceptedFlag'); 
        
        
        $Notifications=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idNotification);
        $this->Kernel->Content->set_data($Notifications->getAdviceRequestQuery()->getId(),'adviser');
        
        
        
        return $this->Kernel;
    }
    
    public function new_advice($idQuery,$idNotification){ 
        $this->unlogged();
        $this->set_read($idNotification);
        
        $this->Kernel->Content->set_data($idNotification,'idNotification');
        
        $UINQuery=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idQuery);
        $UINQuery->check_isExpired();
        
        $QueryData=  $this->get_query_details($idQuery);
        
        $Notifications=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idNotification);
           
        $this->Kernel->Content->set_data($QueryData,'queryDetails');
        $this->Kernel->Content->set_data($Notifications->getAdvices()->getBudget(),'budget');
        $this->Kernel->Content->set_data($Notifications->getAdvices()->getMsg(),'msg');
        
        $this->Kernel->Content->set_data($Notifications->getAdviceRequestQuery()->getUser()->getId(),'adviser');
        
         
        
        return $this->Kernel;
    }
    
    private function get_query_details($idQuery){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $q =  $QB->select("q","q, whats, tag, whens,qwhere,places, finalAsker,cSuppliers, cAdvisers")
            ->from($this->get_repository()[1], 'q') 
            ->leftJoin('q.whats', 'whats')
            ->leftJoin('whats.tag', 'tag')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenAsker', 'whens')
            ->leftJoin('q.finalAsker', 'finalAsker')
            ->leftJoin('q.cSuppliers', 'cSuppliers')
            ->leftJoin('q.cAdvisers', 'cAdvisers')
            ->where('q.id='.$idQuery)
            ->orderBy('q.id','DESC'); 
        $Query=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY)[0];
        
        #set local time
        
//        if($Query['timezone']){
//            $Query['QueryWhenAsker'][0]['date1']->setTimezone(new \DateTimeZone($Query['timezone']));
//        }
        
        
        return $Query;
    }
    
}
