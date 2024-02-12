<?php
namespace Bundle\Controllers\Index;
class Stories  extends \Modules\Modules{
    public $repository=array("Stories");
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;        
    }
    
    function article($id){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('a', 'a')
            ->from($this->get_repository()[0], 'a')
            ->where('a.id=:id')
            ->setParameter('id', $id);
        $Article= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);        
        $this->Kernel->Content->set_data($Article[0],'article');
        
        $meta['title']=$Article[0]['title'];
        $meta['description']=  substr($Article[0]['text'], 0, 150);
        $meta['keywords']=$Article[0]['keywordsString'];
        $this->Kernel->Meta->HtmlMeta->setTitle($meta['title']);
        $this->Kernel->Meta->HtmlMeta->setDescr($meta['description']);
        $this->Kernel->Meta->HtmlMeta->setKeywords($meta['keywords']);
        return $this->Kernel;
    }
    
//    function add_comment(){
//        $User=  $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($_SESSION['user']['id']);
//        $Article=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($this->Kernel->Request->post['idArticle']);
//        $Comment=new \Bundle\Doctrine\Entities\Blog_Comments();
//        $Comment->setText($this->Kernel->Request->post['text']);
//        $Comment->setIdUser($User);
//        $Comment->setBlog($Article);
//        $this->Kernel->entityManager->persist($Comment);
//        $this->Kernel->entityManager->flush();
//        return $this->Kernel;
//    }
//    
//    function get_comments($idArticle){
//        $QB = $this->Kernel->entityManager->createQueryBuilder();
//            $QB->select('c', 'c,a,u')
//            ->from($this->get_repository()[2], 'c')
//            ->leftJoin('c.Blog','a')
//            ->leftJoin('c.idUser','u')
//            ->where('a.id=:id')
//            ->setParameter('id', $idArticle)
//            ->orderBy('c.id','DESC');
//        $Comments= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);       
//        echo json_encode($Comments);
//        return $this->Kernel;
//    }
    
}
