<?php
namespace Modules\Articles;
/**
 * Description of Articles
 * @version "1.0"
 * @author Anastasia
 */
class Articles extends Definitions{
    use \Modules\Data;
    
    function main ($page,$category){ 
        $this->Kernel->compile_assets();
        $this->Kernel->Meta->setAuthor('Esocionika');
        $this->Kernel->Meta->set_twitterSite('@esocionika');
        $this->Kernel->Meta->set_twitterCreator('@esocionika');
        $this->Kernel->Meta->set_twitterCard('summary');
        $this->Kernel->Meta->set_twitterDomain('http://'.$_SERVER['HTTP_HOST']);
        $this->Kernel->Meta->set_ogImage('http://'.$_SERVER['HTTP_HOST'].'/img/bg/logo.png');
        $this->Kernel->Meta->setTitle('Articles on socionics|Encyclopedia Socionika');
        $this->Kernel->Meta->setKeywords('socionics articles, articles on socionics');
        $this->Kernel->Meta->setDescription('Articles on socionics theory, types, quadras and relations');
        $this->Kernel->Meta->set_header('Content-Type', 'text/html');
        $this->Kernel->Meta->add_assets();
        
        /* get articles */
        $Articles = new \Modules\Articles\ArticlesManager($this->entityManager);
        $articlesData=$Articles->get_articles_by_category(6, $page, $category);        
        $articles=$articlesData['data'];        
        foreach ($articles as &$article){
            #create preview for each article
            $noTags=  strip_tags($article['text']);
            $preview=  str_split($noTags, 150);
            $preview=  $preview[0].'...';
            $article['preview']=$preview;
            
            #create title-link for each article
            $link= str_replace('/', '', $article['title']);
            $link= str_replace('\\', '', $link);
            $link=  urlencode($link);            
            $article['link']=$link;
        }
        $this->Kernel->Content->set_data($articles, 'articles');
        
        $pages=$this->set_pagination_pages($articlesData['totalPages'], $page);
        $this->Kernel->Content->set_data($pages, 'pages');
        
        switch ($category) {
            case 'general':
                    $tpl='articles.html.twig';
                break;
            case 'humour':
                    $tpl='humour.html.twig';
                break;
        }
        
        
//        $Response=new \Core\Response();        
//        $Response->set_content($Content)
//                ->set_template($tpl)
//                ->set_meta($Meta)
//                ->render();
    }
    
}
