<?php
namespace Core;
/**
 * Class to handle common asset functions
 * 
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
class DataFactory{
    use Mapping;
    
    public $js;
    public $css;
    public $img;
    public $link;
    public $path;
    public $email;
    
    public function __construct(Kernel $Kernel) {
         $this->Kernel=$Kernel;
    }
       
    /* 
     * Loads assets from the assets JSON file 
     */
    protected function load_assets(){
        return \Core\Utils::read_json($_SERVER['DOCUMENT_ROOT'].'/'.ASSETS_PATH);
    }
    
    /* 
     * Loads Meta assets (css and js) from the map file
     */
    protected function get_page_meta_assets(){ 
        $keys=['css','js'];
        
        $map=$this->get_current_route_map();
        $assets=array_intersect_key($map, array_flip($keys));         
        return $assets;
    }
    
    /* 
     * Get asset Data for direct inserting
     * @param $assets (array) - an array containing all assets (loaded from JSON assets file
     * @param (string) $assetNAme - the name of the asset to be inserted
     * @return - asset
     */
    protected function get_asset($assets, $assetName=NULL){        
        if ($assetName!==NULL){
            foreach($assets as $a){
                if($assetName===$a['name']){                    
                    $asset=$a;
                }                
            }        
        }
        #check if the asset exists at all and throw Exception       
        try {
            if(!isset($asset)||$asset===NULL){                
                throw new \Exception('The asset doesn\'t exist. Asset name: <i>'.$assetName.'</i><br/>');                
            }
            else {
                return $asset;
            }
        } catch (\Exception $E) {
            if(\Dev\Debug::is_dev_mode()){
                echo $E->getMessage();
            }
        }        
    }
    
    /* 
     * Adds asset to the Replace array that will be used to fill in the template
     * @param (string) asset - asset data in a valid format suitable to the type
     * @param (string) type - one of the registred types of of assets
     */
    protected function add_to_replace($asset, $type){
        if ($type==='js' || $type==='css'){
            if (isset($this->{$type}) && is_array($this->{$type})){                
                array_push($this->{$type}, $asset);
            }
            else {            
                $this->{$type}[0]=$asset;
            }
        }
        else if ($type==='img' || $type==="link" || $type==="path" || $type==="email"){
                $this->{$type}[$asset['name']]=$asset;            
        }
    }
}

?>
