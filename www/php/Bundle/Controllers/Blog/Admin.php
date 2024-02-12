<?php
namespace Bundle\Controllers\Blog;
class Admin extends \Modules\Modules{
    use \Modules\Data;
    use \Modules\AdminPanel\Editing;
    
    public $repository=["Blog","Blog_Comments"];
    
    function main(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function manage_article($id=NULL){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        if($id===NULL){
            $Article=new \Bundle\Doctrine\Entities\Blog();
        }
        else{
            $Article=  $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
        }
        $Article->setTitle($this->Kernel->Request->post['title']);
        $Article->setText($this->Kernel->Request->post['text']);
        $Article->setKeywordsString($this->Kernel->Request->post['keywordsString']);
        $this->Kernel->entityManager->persist($Article);
        $this->Kernel->entityManager->flush();
    }
    
    function get_articles($page="1"){
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $data=$DataMngr->get_items_per_page($this->get_repository()[0], 6, $page);
        foreach($data['data']as &$item){
            $item['dateCreated']=$item['dateCreated']->format('Y-m-d H:i:s');
        }
        echo json_encode($data);
    }
    
    function delete_blog_article() {
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $this->delete();
    }
    
    function get_comments($idArticle){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('c', 'c,a,u')
            ->from($this->get_repository()[1], 'c')
            ->leftJoin('c.Blog','a')
            ->leftJoin('c.idUser','u')
            ->where('a.id=:id')
            ->setParameter('id', $idArticle);
        $Comments= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);       
        echo json_encode($Comments);
        return $this->Kernel;
    }
    
    function delete_comment(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $DataMngr->delete_item_by_id($this->get_repository()[1], $this->Kernel->Request->post['id']);
        return $this->Kernel;
    }
    
}

