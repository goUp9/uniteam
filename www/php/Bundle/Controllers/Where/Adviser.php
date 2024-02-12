<?php
namespace Bundle\Controllers\Where;
class Adviser extends \Modules\Modules {
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
            $QueryWhere=new \Bundle\Doctrine\Entities\QueryWhere; 
            $QueryWhere->setPlace($Place);
            $QueryWhere->setRadius($this->Kernel->Request->post['radius']);
            $QueryWhere->setIdQuery($UINQuery);
            $this->Kernel->entityManager->persist($QueryWhere);
            $this->Kernel->entityManager->flush();
        }
        
        return $this->Kernel;
    }
    
    function get_existing_locations(){
        if(isset($this->Kernel->Session->access->currentQuery)&&!empty($this->Kernel->Session->access->currentQuery)){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qwhere, places')
            ->from($this->get_repository()[2], 'q')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->where('q.id='.$this->Kernel->Session->access->currentQuery)  
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            echo json_encode($Queries);
        }
    }
    
    function remove_location(){
        if(\Core\Utils::is_ajax()){
            $DataMngr=new \Modules\DataManager($this->Kernel);
            $DataMngr->delete_item_by_id($this->get_repository()[1], $this->Kernel->Request->post['id']);
        }
    }
            
//    function main(){
//        $Form= new Form($this->Kernel,'form__where','');
//        $this->Kernel->Content->set_form($Form->form,"where");
//        
//        return $this->Kernel;
//    }
//    
//    function confirm(){
//        
//        # check if place is already on the DB
//        $Place=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneByPlaceId($this->Kernel->Request->post['location']['place_id']);
//        
//        #if place doesn't exist - create a new place - otherwise use the existing $Place object
//        if(!is_object($Place)){ // set place table if place doesn't exist
//            $Place=new \Bundle\Doctrine\Entities\Places();
//            $Place->setPlaceId($this->Kernel->Request->post['location']['place_id']);
//            $Place->setLat($this->Kernel->Request->post['location']['lat']);
//            $Place->setLng($this->Kernel->Request->post['location']['lng']);
//            $Place->setFormattedAddress($this->Kernel->Request->post['location']['formatted_address']);
//            $Place->setDateCreated();
//            $this->Kernel->entityManager->persist($Place);
//            $this->Kernel->entityManager->flush();            
//        }
//        
//        #set up Query and QueryWhere tables
//        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($this->Kernel->Session->access->currentQuery);
//        if(is_object($UINQuery)){ // check if the query exists (user has to be logged in for that            
//            $QueryWhere=new \Bundle\Doctrine\Entities\QueryWhere; 
//            $QueryWhere->setPlace($Place);
//            $QueryWhere->setRadius($this->Kernel->Request->post['radius']);
//            $QueryWhere->setIdQuery($UINQuery);
//            $this->Kernel->entityManager->persist($QueryWhere);
//            $this->Kernel->entityManager->flush();
//        }
//        
//        return $this->Kernel;
//    }
}
