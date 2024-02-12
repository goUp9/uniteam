<?php
namespace Modules\Articles;
/**
 * Description of Article
 * @version '2.0'
 * @author Anastasia
 */
class Article extends Definitions{    
        
    function main ($title,$id,$category){
        
        $this->Kernel->compile_assets();
        $this->Kernel->Meta->setAuthor('Esocionika');
        $this->Kernel->Meta->set_twitterSite('@esocionika');
        $this->Kernel->Meta->set_twitterCreator('@esocionika');
        $this->Kernel->Meta->set_twitterCard('summary');
        $this->Kernel->Meta->set_twitterDomain('http://'.$_SERVER['HTTP_HOST']);
        $this->Kernel->Meta->set_ogImage('http://'.$_SERVER['HTTP_HOST'].'/img/bg/logo.png');
        
        $this->Kernel->Meta->add_assets();
        
        /* get article */
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $article=$DataMngr->get_item_by_id($this->Kernel->repository, $id);       
        $this->Kernel->Content->set_data($article, 'article');
        $this->Kernel->Meta->setTitle(urldecode($title));
        
        #create preview for meta description
        $noTags=  strip_tags($article['text']);
        $preview=  str_split($noTags, 150);
        $preview=  $preview[0].'...';
        $article['preview']=$preview;
        $this->Kernel->Meta->setDescription($preview);
        $this->Kernel->Meta->setDescription($preview);
        
        #set keywords for this article
        $this->Kernel->Meta->setKeywords($article['keywords']);      
        
        return $this->Kernel;
        
    }
    
}
