<?php
namespace Core\Metadata;
class HtmlMeta {
    use \Core\SystemAssets;
    
    private $title='';
    private $descr='';
    private $keywords='';    
    
    public function __construct($map) {
        if(isset($map['title'])&&!empty($map['title'])){
            $this->title=$map['title'];
        }
        if(isset($map['description'])&&!empty($map['description'])){
            $this->descr=$map['description'];
        }
        $keywords=$this->make_keywords($map);
        $this->keywords=$keywords;
    }
    
    function getTitle(){
        return $this->title;
    }
    
    function setTitle($title){
        $this->title=$title;
    }
    
    function getDescr(){
        return $this->descr;
    }
    
    function setDescr($descr){
        $this->descr=$descr;
    }
    
    function getKeywords(){
        return $this->keywords;
    }
    
    function setKeywords($keywords){
        $this->keywords=$keywords;
    }
    
    
}
