<?php
namespace Bundle\Controllers\Matching;
class Selection  extends \Modules\Modules{
    
    public $repository=array("UINQuery", "Users");
    
    public function main($id){
        $idQuery=$id;
       
        #remove cron job
        #UNCOMMENT FOR LIVE VERSION
        $CrontabMngr=new \CrontabManager\CrontabManager();
        if($CrontabMngr->jobExists('select-suppliers\/'.$idQuery)){
            $CrontabMngr->deleteJob('select-suppliers\/'.$idQuery);
            $CrontabMngr->save(false);
        }
        
        
        $SuppliersData=$this->get_suppliers($idQuery)->toArray();
        $suppliers=[];
//        \Dev\Debug::dump($SuppliersData);
        if(!empty($SuppliersData)){

            foreach($SuppliersData as $s){                   
                  $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(['idUser'=>$s->getId(),'type'=>'supply']);
                  $experience=$UINQuery->get_finalSupplier()->getExperience();
                  $qualification=$UINQuery->get_finalSupplier()->getQualification();
                  $feedback=  $this->get_feedback($s);                  
                  $ranking=$this->get_ranking($experience, $qualification,$feedback);                  
                  array_push($suppliers, ['id'=>$s->getId(),'username'=>$s->getUsername(),'ranking'=>$ranking]);                  
            }
            $TheChosenOne=$this->match_them($suppliers);
            
            #set data to db
            $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
            $User=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($TheChosenOne['id']);
            $UINQuery->set_chosenSupplier($User);
            $UINQuery->setStatus('Supplier found: '.$User->getUsername());
            $UINQuery->setIsAwaitingEscrow(TRUE);
                               
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();

            #new notifications
            $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->getUser(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_SUPPLIER_SELECTED);            
            
            $Notification->send();
            #email
            $replacementArray['queryData']['id']=$UINQuery->getId();
            $replacementArray['username']=$UINQuery->getUser()->getUsername();
            $ENotifications=new \Modules\UserNotifications\Email($UINQuery->getUser(), 'Supplier for your Query #'.$UINQuery->getId().' has been selected on uinteam', 'supplier_selected.html.twig', $replacementArray);
            
            $ENotifications->send();   
        }
        else { // no suppliers
            $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
            $UINQuery->setStatus('No suitable suppliers found.');
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();
        }
        $UINQuery->setIsExpired(TRUE);
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();
    }
    
    private function get_feedback($Supplier){
        $id=$Supplier->getId();
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(['idUser'=>$id,'type'=>'supply']);
        $feedbacks=$Query->getSuppliersFeedback()->toArray();
        
        $i=0;
        $rank=0;
        if(!empty($feedbacks)){
            foreach($feedbacks as $f){
                $rank+=$f->getFeedback();
                $i++;
            }
            $feedback=$rank/$i;
        }
        else{
            $feedback=0;
        }
        return $feedback;
    }


    private function get_suppliers($idQuery){        
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        
        $SuppliersData=$UINQuery->getCSuppliers();       

        return $SuppliersData;
    }
    
    private function get_ranking($experience, $qualification, $feedback=0){
        return $experience*25/50+$qualification*25/5+50*$feedback/5;
    }
    
    private function match_them($arr){
        $max = -9999999;
        $found_item = null;

        foreach($arr as $k=>$v){
            if($v['ranking']>$max){
               $max = $v['ranking'];
               $found_item = $v;
            }
        }
        return $found_item;
    }
    
}
