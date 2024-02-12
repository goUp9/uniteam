<?php
namespace Core\Metadata;
class TwitterMeta {
    use \Core\SystemAssets;
    
    private $twitterCreator;
    private $twitterCard;   
    private $twitterSite;
    private $twitterDomain;
    
    public function __construct($map) {
        if(isset($map['twitter'])&&!empty($map['twitter'])){
            if(isset($map['twitter']['creator'])&&!empty($map['twitter']['creator'])){
                $this->twitterCreator=$map['twitter']['creator'];
            }
            if(isset($map['twitter']['card'])&&!empty($map['twitter']['card'])){
                $this->twitterCard=$map['twitter']['card'];
            }
            if(isset($map['twitter']['site'])&&!empty($map['twitter']['site'])){
                $this->twitterSite=$map['twitter']['site'];
            }
            if(isset($map['twitter']['domain'])&&!empty($map['twitter']['domain'])){
                $this->twitterDomain=$map['twitter']['domain'];
            }
        }
    }
    
    function get_twitterCreator(){
        return $this->twitterCreator;
    }
    
    function get_twitterCard(){
        return $this->twitterCard;
    }
    
    function get_twitterSite(){
        return $this->twitterSite;
    }
    
    function get_twitterDomain(){
        return $this->twitterDomain;
    }
    
    function set_twitterCreator($twitterCreator){
        $this->twitterCreator=$twitterCreator;
        return $this;
    }
    
    function set_twitterCard($twitterCard){
        $this->twitterCard=$twitterCard;
        return $this;
    }
    
    function set_twitterSite($twitterSite){
        $this->twitterSite=$twitterSite;
        return $this;
    }
    
    function set_twitterDomain($twitterDomain){
        $this->twitterDomain=$twitterDomain;
        return $this;
    }
    
    
}
