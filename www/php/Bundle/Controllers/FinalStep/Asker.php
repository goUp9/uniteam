<?php
namespace Bundle\Controllers\FinalStep;
class Asker extends \Modules\Modules{
    public $repository=['FinalAsker','UINQuery'];
    
    function main(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('adviseBox_label_msg','adviseBox_label_budget')); 
        
        return $this->Kernel;
    }
    
    function confirm(){
        if(isset($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            $query_id=$this->Kernel->Session->access->currentQuery;
        }
        
        $this->remove_existing($query_id);
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);
        
        $FiAsker=new \Bundle\Doctrine\Entities\FinalAsker();
        $FiAsker->setBudget($this->Kernel->Request->post['budget']);
        $FiAsker->setCurrency($this->Kernel->Request->post['currency']);
        
        if(isset($this->Kernel->Request->post['msg'])){
            $FiAsker->setMsg($this->Kernel->Request->post['msg']);
        }
        $FiAsker->setUINQuery($UINQuery);
        
        $this->Kernel->entityManager->persist($FiAsker);
        $this->Kernel->entityManager->flush();
        
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();       
        
        return $this->Kernel;
    }
    
    function confirm_for_advice(){
        if(isset($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            $query_id=$this->Kernel->Session->access->currentQuery;
        }
        
        $this->remove_existing($query_id);
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);

        $UINQuery->get_FinalAsker()->setAdviceMsg($this->Kernel->Request->post['adviceMsg']);
        if(isset($this->Kernel->Request->post['isAdviseOnBudgetNeeded'])&&$this->Kernel->Request->post['isAdviseOnBudgetNeeded']=="true"){
            $UINQuery->get_FinalAsker()->setIsAdviseOnBudgetNeeded(TRUE);
        }
        else {
            $UINQuery->get_FinalAsker()->setIsAdviseOnBudgetNeeded(FALSE);
        }
        $UINQuery->get_FinalAsker()->setUINQuery($UINQuery);
        

        
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();       
        
        return $this->Kernel;
    }
    
    function remove_existing($idQuery){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->delete($this->get_repository()[0],'fa')  
            ->where('fa.UINQuery='.$idQuery); 
        $query = $QB->getQuery();
        $query->getResult();
    }
}
