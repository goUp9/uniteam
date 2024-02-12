<?php
namespace Core;
/**
 * Creates the dynamic content of the web page:
 * data and forms
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
class ContentFactory extends DataFactory {
    use SystemAssets;
    
    public $forms;
    public $data;
    public $sessions=array();
    
    /* 
    * sets the data array to be inserted into the template
    * @param (mixed) $data - variable with the data to use on the template
    * @param (string) $title - the name of the index in the "data" array
     */
    public function set_data($data,$title){
        $this->data[$title]=$data;
        return $this;
    }
    
    /*
    * sets the form to be inserted into the template
    * @param (string) $data - variable with the form as a string to use on the template
    * @param (string) $title - the name of the index in the "data" array 
     */
    public function set_form($data,$title){
        $this->forms[$title]=$data;
        return $this;
    }
    
    /* 
     * Main function to call to get full access to the assets     * 
     * inits the autoload assets 
     */
    public function init_assets(){
        $assets=$this->load_assets();
        if(isset($assets['img'])&&!empty($assets['img'])){
            $this->set_imgs($assets['img']); // set image assets
        }
        if(isset($assets['link'])&&!empty($assets['link'])){
            $this->set_links($assets['link']); //set link assets
        }
        if(isset($assets['path'])&&!empty($assets['path'])){
            $this->set_paths($assets['path']);
        }
        if(isset($assets['email'])&&!empty($assets['email'])){
            $this->set_emails($emails);
        }
    }
    
    /*
     *  requesting an asset outside of a Controller e.g. in a form
     *  @param (string) $assetType
     *  @param (string) $assetName
     */
    public function insert_asset($assetType,$assetName){
        $assets=$this->load_assets();
        $assets=$assets[$assetType]; // narrow to assets of the requested type
        $asset=  $this->get_asset($assets, $assetName);
        switch ($assetType) {
            case 'img':
                    $asset=$this->make_imgAsset($asset);
                break;
            case 'email':
                    $asset=$this->make_emailAsset($asset);
                break;
        }        
        return $asset;
    }
    
    /* 
     * sets images from the Assets
     * @param (array) $imgs - $assets['img'] from the JSON file with assets
     * @return (void)
     */
    private function set_imgs($imgs){
        foreach($imgs as $img){
            $img=$this->make_imgAsset($img);
            $this->add_to_replace($img, 'img');            
        }
    }
    
    /* 
     * sets links from the Assets
     * @param (array) $links - $assets['link'] from the JSON file with assets
     * @return (void)
     */
    private function set_links($links){
        foreach($links as $link){ 
            $link=$this->make_linkAsset($link);
            $this->add_to_replace($link, 'link');
        }
    }
    
    /* 
     * sets paths from the Assets
     * @param (array) $paths - $assets['path'] from the JSON file with assets
     * @return (void)
     */
    private function set_paths($paths){
        foreach($paths as $path){
            $path=$this->make_pathAsset($path);
            $this->add_to_replace($path, 'path');
        }
    }
    
    /* 
     * sets emails from the Assets
     * @param (array) $emails - $assets['email'] from the JSON file with assets
     * @return (void)
     */
    private function set_emails($emails){
        foreach($emails as $email){            
            $email=$this->make_emailAsset($email);
            $this->add_to_replace($email, 'email');
        }
    }
    
}

?>
