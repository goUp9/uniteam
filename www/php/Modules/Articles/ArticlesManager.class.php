<?php
namespace Modules\Articles;
class ArticlesManager extends Definitions {
        
    public function get_articles_by_category($num_per_page,$currentPageNumber=0, $category){
        # count all articles
        $q = $this->Kernel->entityManager->createQuery("select COUNT(a.id) from ".$this->repository." a WHERE a.category='".$category."' ORDER BY a.id DESC");        
        $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
        
        $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page, $currentPageNumber);
        
        
        $q = $this->Kernel->entityManager->createQuery("select a from ".$this->repository." a WHERE a.category='".$category."' ORDER BY a.id DESC")
                ->setMaxResults($num_per_page)
                ->setFirstResult($pagination['offset']);                      
        $articles=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);  
        $pagination['data']=$articles;
        return $pagination;
    }
}

?>
