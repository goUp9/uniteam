<?php
namespace Bundle\Controllers\Where;
class EditWhere extends \Modules\Users\Profile{
    public $repository=['Places','QueryWhere','UINQuery'];
    
    function main(){
        $this->unlogged();
                
        $this->Kernel->Content->set_data($this->Kernel->Request->get['type'],'linkBack');
        
        $Form=new EditForm($this->Kernel, 'form__edit','',  $this->get_init_data());


        $this->Kernel->Content->set_form($Form->form,'edit_where');
        
        return $this->Kernel;
    }
    
    function edit_data(){
        $this->unlogged();
       
        if(!empty($this->Kernel->Request->post['query_where_id'])){
            $QueryWhere=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($this->Kernel->Request->post['query_where_id']);
        }
        else {
            $QueryWhere=new \Bundle\Doctrine\Entities\QueryWhere();
        }
        
        if(!empty($this->Kernel->Request->post['radius'])){
            $QueryWhere->setRadius($this->Kernel->Request->post['radius']);
        }
        else {
            if(!$QueryWhere->getRadius()){
                $QueryWhere->setRadius(0);
            }
        }
        
        if(!empty($this->Kernel->Request->post['location']['place_id'])){
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
            $QueryWhere->setPlace($Place);
        }
        
        if(!empty($this->Kernel->Request->post['query_where_id'])){ 
            $DataMngr=new \Modules\DataManager($this->Kernel);
            $DataMngr->delete_item_by_id($this->get_repository()[1], $this->Kernel->Request->post['query_where_id']);            
        }
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($this->Kernel->Request->post['query_id']);
        $QueryWhere->setIdQuery($Query);
        $this->Kernel->entityManager->persist($QueryWhere);
        $this->Kernel->entityManager->flush();
    }
    
    private function get_init_data(){
        if(isset($this->Kernel->Request->get['place_id'])&&!empty($this->Kernel->Request->get['place_id'])){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qwhere, places')
            ->from($this->get_repository()[2], 'q')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->where('q.id='.$this->Kernel->Request->get['query_id'])
            ->andWhere("places.placeId='".$this->Kernel->Request->get['place_id']."'")
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }
        else {
            $Queries=FALSE;
        }
        return $Queries;
    }
}
