<?php
namespace Bundle\Controllers\Tags;
class Admin extends \Modules\Modules{
    
    public $repository=array("Tags","QueryWhat","TagGroups");
    
    function list_tags(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
                
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function get_tags(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        if(\Core\Utils::is_ajax()){
            if(empty($this->Kernel->Request->post['searchVal'])){
                $usersData=  $this->get_items_by_page($this->get_repository()[0], 10,$this->Kernel->Request->post['page'],$this->Kernel->Request->post['orderBy']);               
            }
            else {
                $usersData=$this->get_items_by_search_and_page($this->get_repository()[0], 10,$this->Kernel->Request->post['page'],$this->Kernel->Request->post['orderBy']);
            }
            echo json_encode($usersData);
        }
        return $this->Kernel;
    } 
    
    function get_taggroups(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $q=$this->Kernel->entityManager->createQuery("select cat from ".$this->get_repository()[2]." cat ORDER BY cat.name ASC");   
        $TagGroups=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY); 
        $Response=new \Core\AjaxResult(1, '', $TagGroups);        
        echo $Response->to_JSON();
    }
   
    
    function save_change(){ 
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $this->Kernel->Request->post['old']=trim($this->Kernel->Request->post['old']);
        $this->Kernel->Request->post['tag']=  trim($this->Kernel->Request->post['tag']);
        $Tag=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array('tag'=>$this->Kernel->Request->post['old']));
        $Tag->setTag($this->Kernel->Request->post['tag']);
        if($this->Kernel->Request->post['status']==="true"){
            $Tag->setStatus(TRUE);
        }
        else {
            $Tag->setStatus(FALSE);
        }
        $this->Kernel->entityManager->persist($Tag);
        $this->Kernel->entityManager->flush();
    }
    
    function save_taggroup_change(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $Tag=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array('tag'=>$this->Kernel->Request->post['tag']));
        $TagGroup=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneBy(array('id'=>$this->Kernel->Request->post['id']));
        $Tag->setTagGroup($TagGroup);
        $this->Kernel->entityManager->persist($Tag);
        $this->Kernel->entityManager->flush();
    }
    
    private function get_items_by_page($repository,$num_per_page,$currentPageNumber, $orderBy){
        # count all
        $q = $this->Kernel->entityManager->createQuery("select COUNT(a.tag) from ".$repository." a ORDER BY a.tag DESC");        
        $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
        
        $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page, $currentPageNumber);
        
        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('t.tag, tg.id, t.status, t.dateCreated, tg.name', 't.tag','u.username','u.id')
        ->from($this->get_repository()[0], 't')
        ->leftJoin('t.tagGroup', 'tg')
        ->leftJoin('t.idUser', 'u')
        ->setMaxResults($num_per_page)
        ->setFirstResult($pagination['offset'])
        ->orderBy($orderBy,'ASC');
        $data= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        $pagination['data']=$data;
        return $pagination;
    }
    
    private function get_items_by_search_and_page($repository,$num_per_page,$currentPageNumber,$orderBy){
        # count all
        $q = $this->Kernel->entityManager->createQuery("select COUNT(a.tag) from ".$repository." a WHERE a.tag LIKE '".$this->Kernel->Request->post['searchVal']."%' ORDER BY a.tag DESC");        
        $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
        
        $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page, $currentPageNumber);
        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('t.tag, tg.id, t.status, t.dateCreated, tg.name', 't.tag','u.username','u.id')
        ->from($this->get_repository()[0], 't')
        ->leftJoin('t.tagGroup', 'tg')
        ->leftJoin('t.idUser', 'u')
        ->where('t.tag LIKE :search')
        ->setMaxResults($num_per_page)
        ->setFirstResult($pagination['offset'])
        ->orderBy($orderBy,'ASC')
        ->setParameter('search', "%".$this->Kernel->Request->post['searchVal']."%");
        $data= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
          
        $pagination['data']=$data;
        return $pagination;
    }
}
