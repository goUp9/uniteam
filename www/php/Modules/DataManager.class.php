<?php
namespace Modules;
class DataManager {
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    public function get_item_by_id($repository, $id){
        $q = $this->Kernel->entityManager->createQuery("SELECT a FROM ".$repository." a WHERE a.id='".$id."'");
        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $data=  array_shift($data);
        return $data;
    }
    
    public function get_items($repository){
        $q = $this->Kernel->entityManager->createQuery("SELECT a FROM ".$repository." a ");
        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $data;
    }
    
    public function get_items_per_page($repository,$num_per_page,$currentPageNumber=1, $orderBy="ORDER BY a.id DESC", $idDbColumn='id'){
        # count all
        $q = $this->Kernel->entityManager->createQuery("select COUNT(a.".$idDbColumn.") from ".$repository." a ".$orderBy);        
        $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
        
        $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page, $currentPageNumber);
        
        
        $q = $this->Kernel->entityManager->createQuery("select a from ".$repository." a ".$orderBy)
                ->setMaxResults($num_per_page)
                ->setFirstResult($pagination['offset']);                      
        $data=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);  
        $pagination['data']=$data;
        return $pagination;
    }
    
    public function create_new_item($repository,$data){        
        $r=new \ReflectionClass($repository);
        $entry = $r->newInstanceArgs();        
        foreach($data as $key=>$d){                
                $method='set'.ucfirst(str_replace('_','',  ucfirst($key)));                
                if(is_callable(array($entry,$method))){ 
                    if($d==="true"){
                        $d=true;
                    }
                    if($d==="false"){
                        $d=false;
                    }
                    call_user_func_array(array($entry,$method), array($d));     
                }
        }
        $this->Kernel->entityManager->persist($entry);
        $this->Kernel->entityManager->flush();
        if(is_callable(array($entry,'getId'))){
            return $entry->getId();
        }
    }
    
    public function update_item_by_id($id, $repository, $data, $idField='id'){             
            $entry = $this->Kernel->entityManager->getRepository($repository)->findOneBy(array($idField=>$id));
            foreach($data as $key=>$d){
                $method='set'.ucfirst(str_replace('_','',  ucfirst($key)));                
                if(is_callable(array($entry,$method))){
                    if($d==="true"){
                        $d=true;
                    }
                    if($d==="false"){
                        $d=false;
                    }
                    call_user_func_array(array($entry,$method), array($d));     
                }
            }
            $this->Kernel->entityManager->persist($entry);
            $this->Kernel->entityManager->flush();
    }
    
    public function delete_item_by_id($repository, $id){
        $entry = $this->Kernel->entityManager->getRepository($repository)->findOneBy(array('id'=>$id)); 
        $this->Kernel->entityManager->remove($entry);
        $this->Kernel->entityManager->flush();
    }
    
}

?>
