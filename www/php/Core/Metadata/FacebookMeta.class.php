<?php
namespace Core\Metadata;
class FacebookMeta {
    use \Core\SystemAssets;
    
    private $ogImage;   
    
    public function __construct($map) {
        if(isset($map['ogImage'])&&!empty($map['ogImage'])){
            $this->title=$map['ogImage'];
        }        
    }
    
    function get_OgImage(){
        return $this->ogImage;
    }
    
    function set_OgImage($ogImage){
        $this->ogImage=$ogImage;
    }
    
    
}
