<?php
namespace Bundle\Controllers\FinalStep;
class Supplier extends \Modules\Modules{
    public $repository=['FinalSupplier','UINQuery'];
    
    function confirm(){
        if(isset($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            $query_id=$this->Kernel->Session->access->currentQuery;
        }
        
        $this->remove_existing($query_id);
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);
        
        $FiSupplier=new \Bundle\Doctrine\Entities\FinalSupplier();
        $FiSupplier->setExperience($this->Kernel->Request->post['experience']);
        $FiSupplier->setQualification($this->Kernel->Request->post['qualification']);
        $FiSupplier->setUINQuery($UINQuery);
        
        $this->Kernel->entityManager->persist($FiSupplier);
        $this->Kernel->entityManager->flush();
                
        
        return $this->Kernel;
    }
    
    function remove_existing($idQuery){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->delete($this->get_repository()[0],'fs')  
            ->where('fs.UINQuery='.$idQuery); 
        $query = $QB->getQuery();
        $query->getResult();
    }
}
