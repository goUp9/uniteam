<?php
namespace Bundle\Controllers\Payments;
class Admin  extends \Modules\Modules{
    public $repository=array("UINQuery","AdminPayments");
    
    public function settings(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $Settings = $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById(1);
        
        if(!is_object($Settings)){
            $Settings=new \Bundle\Doctrine\Entities\AdminPayments();
        }
        if(!empty($this->Kernel->Request->post)){
            if(isset($this->Kernel->Request->post['isLive'])){
                if($this->Kernel->Request->post['isLive']==="on"){
                    $Settings->setIsLive(TRUE);
                }
                else {
                    $Settings->setIsLive(FALSE);
                }
            }
            else {
                $Settings->setIsLive(FALSE);
            }
            if(isset($this->Kernel->Request->post['account'])){
                $Settings->setAccount($this->Kernel->Request->post['account']);
            }
            $this->Kernel->entityManager->persist($Settings);
            $this->Kernel->entityManager->flush();
        }
        
        $this->Kernel->Content->set_data($Settings->getAccount(),'account');
        if($Settings->getIsLive()){
            $this->Kernel->Content->set_data(TRUE,'isLive');
        }
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    public function reminder($idQuery){
        if($idQuery!==''){
            $PHPMailer=new \PHPMailer();
            $PHPMailer->Body='Reminder to make a payment for Query #'.$idQuery.'.';
            $PHPMailer->Subject='UINTeam Payment Reminder';
            $PHPMailer->addAddress('eng.szappala@gmail.com');
            $PHPMailer->send();
            
            $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
            if(is_object($UINQuery)){
                $UINQuery->setStatus('Awaiting payment');
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush();
            }
            
            $CrontabMngr=new \CrontabManager\CrontabManager();
            if($CrontabMngr->jobExists('admin-payment-reminder\/'.$idQuery)){
                $CrontabMngr->deleteJob('admin-payment-reminder\/'.$idQuery);
                $CrontabMngr->save(false);
            }
        }
    }
    
    public function supplier_paid($idQuery){
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        if(is_object($UINQuery)){
            #amount
            $budget=$UINQuery->get_FinalAsker()->getBudget();
            
            if($UINQuery->get_ChosenAdviser()!=NULL && $UINQuery->getAdviserPaid()){
                $UINQuery->setSupplierPaid();
                $supplierAmount=$budget-$budget*0.05;
                $UINQuery->setUinPaidAmount(round($budget*0.05,2));
                $UINQuery->setStatus('Completed');
            }
            else if($UINQuery->get_ChosenAdviser()!=NULL && !$UINQuery->getAdviserPaid()){
                $UINQuery->setSupplierPaid();
                $supplierAmount=$budget-$budget*0.05;
            }
            else {
                $UINQuery->setSupplierPaid();                
                $supplierAmount=$budget-$budget*0.05;
                $UINQuery->setUinPaidAmount(round($budget*0.05,2));
                $UINQuery->setStatus('Completed');
            }
            
            $UINQuery->setSupplierPaidAmount(round($supplierAmount,2));
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();
            
            #new notifications
            $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->get_ChosenSupplier(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_SUPPLIER_PAID);                       
            $Notification->send();
            
            #email
            $replacementArray['username']=$UINQuery->get_ChosenSupplier()->getUsername();
            $ENotifications=new \Modules\UserNotifications\Email($UINQuery->get_ChosenSupplier(), 'Payment for your services has been made', 'supplier_paid.html.twig', $replacementArray);
            $ENotifications->send();  
        }
    }
    
    public function adviser_paid($idQuery){
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
        if(is_object($UINQuery)){
            #amount
            $budget=$UINQuery->get_FinalAsker()->getBudget();
            $adviserAmount=$budget*0.02;  
            if($UINQuery->getSupplierPaid()){ 
                $UINQuery->setUinPaidAmount(round($budget*0.03,2));
                $UINQuery->setStatus('Completed');
            }
            $UINQuery->setAdviserPaid(); 
            $UINQuery->setAdviserPaidAmount(round($adviserAmount,2));
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();
            
            #new notifications
            $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->get_ChosenAdviser(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_ADVISER_PAID);                       
            $Notification->send();
            
            #email
            $replacementArray['username']=$UINQuery->get_ChosenAdviser()->getUsername();
            $ENotifications=new \Modules\UserNotifications\Email($UINQuery->get_ChosenAdviser(), 'Payment for your advice has been made', 'adviser_paid.html.twig', $replacementArray);
            $ENotifications->send();  
        }
    }
}
