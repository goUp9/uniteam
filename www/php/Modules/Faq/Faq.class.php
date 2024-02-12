<?php
namespace Modules\Faq;
class Faq extends \Modules\DataManager{
    public $repository="Bundle\Doctrine\Entities\Faq";
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    public function get_all_faq(){
        $q = $this->Kernel->entityManager->createQuery("select f from ".$this->repository." f ORDER BY f.id DESC");
        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $data;
    }
    
    public function get_faq_per_page($num_per_page,$currentPageNumber=1,$orderBy="ORDER BY a.status DESC"){
        $pagination=$this->get_items_per_page($this->repository, $num_per_page, $currentPageNumber,$orderBy);
        return $pagination;
    }
    
    public function get_published_faq(){
        $q = $this->Kernel->entityManager->createQuery("select f from ".$this->repository." f WHERE f.status=1 ORDER BY f.id DESC");
        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $data;
    }
    
    public function get_single_faq($id){
        $data=$this->get_item_by_id($this->repository, $id);
        return $data;
    }
    
    public function edit_faq($id,$data){
        $this->update_item_by_id($id, $this->repository, $data);
    }
    
    public function create_faq($data){
        $this->create_new_item($this->repository, $data);
    }
    
    public function delete_faq($id){
        $this->delete_item_by_id($this->repository, $id);
    }
    
}

?>
