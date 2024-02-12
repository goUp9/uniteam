<?php
namespace Bundle\Controllers\Matching;
class AcceptBySupplier extends \Modules\Users\Profile{
    
    public $repository=array("UINQuery", "Users");
    
    function main($idQuery){
        $this->unlogged();
        $idSupplier=$_SESSION['user']['id'];
       
        $User = $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($idSupplier);
        $paypal=$User->getPaypal();
        if(isset($paypal)&&!empty($paypal)){
            $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
            if(!$UINQuery->getCSuppliers()->contains($User)){
                $UINQuery->setCSuppliers($User);
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush();
            }
            $this->Kernel->Content->set_data('The request has been accepted','msg');
        }
        else {
            $link=$this->Kernel->Content->insert_asset('link','myuin__personal');
            $this->Kernel->Content->set_data('Please set up your paypal account in your <a href="'.LINKS_PRE.$link['href'].'">myuin</a> to accept payments first.','msg');
        }
//        \Dev\Debug::dump($idQuery);
        return $this->Kernel;
    }
    
}
