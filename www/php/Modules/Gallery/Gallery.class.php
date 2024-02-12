<?php
namespace Modules\Gallery;
class Gallery extends \Modules\DataManager{
    public $repository='Bundle\Doctrine\Entities\Gallery';
    
    
    function get_gallery_per_page($num_per_page,$currentPageNumber=1){
        return $this->get_items_per_page($this->repository,$num_per_page, $currentPageNumber);        
    }
    
    function get_gallery_by_category($num_per_page,$currentPageNumber=0, $category){
        # count all articles        
        $q = $this->Kernel->entityManager->createQuery("select COUNT(g.id) from ".$this->repository." g WHERE g.gallery_type='".$category."' ORDER BY g.id DESC");        
        $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
        
        $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page, $currentPageNumber);        
        
        $q = $this->Kernel->entityManager->createQuery("select g from ".$this->repository." g WHERE g.gallery_type='".$category."'  ORDER BY g.id DESC")
                ->setMaxResults($num_per_page)
                ->setFirstResult($pagination['offset']);                      
        $gallery=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);  
        $pagination['data']=$gallery;
        return $pagination;
    }
}

?>
