<?php
namespace Core;
/**
 * Creates the dynamic Meta of the web page:
 * css,js, meta tags
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
class MetadataFactory extends DataFactory {    
    use Mapping,SystemAssets;
    public $HtmlMeta;
    public $FacebookMeta;
    public $TwitterMeta;
    public $headers;
    
    public function __construct(Kernel $Kernel) {
        $this->Kernel=$Kernel;
        
        $map=$this->get_current_route_map();
        $this->HtmlMeta=new Metadata\HtmlMeta($map);
        $this->FacebookMeta=new Metadata\FacebookMeta($map);
        $this->TwitterMeta=new Metadata\TwitterMeta($map);
    }
    
    
    function set_header($type, $header){
        $this->headers[$type]=$header;
        return $this;
    }
    
    /* 
     * Main function to call to add Meta assets to the page 
     * inits the autoload assets 
     */
    function add_assets(){        
        $assets=$this->get_page_meta_assets();
        #CSS ASSETS
        if(isset($assets['css'])&& !empty($assets['css'])){
            foreach ($assets['css'] as $css){            
                $asset=$this->make_cssAsset($css);           
                $this->add_to_replace($asset, 'css');
            }
        }
        #JS ASSETS        
        if($_SESSION['development_mode']){ // if Dev mode is on
            if(isset($assets['js'])&& !empty($assets['css'])){
                foreach ($assets['js'] as $js){            
                    $asset=$this->make_jsDevAsset($js);
                    $this->add_to_replace($asset, 'js'); 
                }
            }
        }
        else { // production
            $map=$this->get_current_route_map();
            $asset=$this->make_jsAsset($map);
            $this->add_to_replace($asset, 'js');
        }
    }
    
    /*
     *  requesting an asset outside of a Controller e.g. in a form
     *  @param (string) $assetType
     *  @param (string) $assetName
     */
    public function insert_asset($assetType,$assetName){
        $assets=$this->load_assets();
        $assets= $assets[$assetType];
        $asset=  $this->get_asset($assets, $assetName);
        switch ($assetType) {
            case 'css': 
                        $asset=$this->make_cssAsset($asset);
                break;
            case 'js':
                        $asset=$this->make_jsDevAsset($js);
                break;
        }
        return $asset;
    }
}

?>
