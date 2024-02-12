<?php
namespace Bundle\Controllers\Where;
class Asker extends \Modules\Modules {
    public $repository=['Places','QueryWhere','UINQuery'];
            
    function main(){
        $Form= new Form($this->Kernel,'form__where','');
        $this->Kernel->Content->set_form($Form->form,"where");
        
        return $this->Kernel;
    }
    
    function confirm(){
        
        # check if place is already on the DB
        $Place=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneByPlaceId($this->Kernel->Request->post['location']['place_id']);
        
        #if place doesn't exist - create a new place - otherwise use the existing $Place object
        if(!is_object($Place)){ // set place table if place doesn't exist
            $Place=new \Bundle\Doctrine\Entities\Places();
            $Place->setPlaceId($this->Kernel->Request->post['location']['place_id']);
            $Place->setLat($this->Kernel->Request->post['location']['lat']);
            $Place->setLng($this->Kernel->Request->post['location']['lng']);
            $Place->setFormattedAddress($this->Kernel->Request->post['location']['formatted_address']);
            $Place->setDateCreated();
            $this->Kernel->entityManager->persist($Place);
            $this->Kernel->entityManager->flush();            
        }
        
        #set up Query and QueryWhere tables
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($this->Kernel->Session->access->currentQuery);
        if(is_object($UINQuery)){ // check if the query exists (user has to be logged in for that
            $QueryWhere=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneByIdQuery($UINQuery->getId());
            if(!is_object($QueryWhere)){ // if Query already exist - update (ASKER has only one location)
                $QueryWhere=new \Bundle\Doctrine\Entities\QueryWhere;                
            }
            $QueryWhere->setPlace($Place);
            \Dev\Debug::dump($QueryWhere->getPlace());
            $QueryWhere->setRadius($this->Kernel->Request->post['radius']);
            $QueryWhere->setIdQuery($UINQuery);
            $this->Kernel->entityManager->persist($QueryWhere);
            $this->Kernel->entityManager->flush();
        }
        
        return $this->Kernel;
    }
    
}
