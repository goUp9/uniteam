<?php
namespace Dev;
/**
 * Description of AssetsManager
 * @package:
 * @author: Anastasia Sitnina
 * @version:
 */
class AssetsManager {
    
    public $pathToImgs='/../../img/assets/';
    public $pathToCss='/../../css/';
    public $pathToJs='/../../js/';
    public $maps='/../../config/maps.json';
    public $assets='/../../config/assets.json';
    
    /* creates links based on the maps config file */
    function generate_links(){
        $maps= \Core\Utils::read_json(dirname(__FILE__).$this->maps);
        $assets=  \Core\Utils::read_json(dirname(__FILE__).$this->assets);        
        $requests=array();
        try{
            if(is_array($maps['routes'])){
                for($i=0; $i<count($maps['routes']);$i++){
                    $requests[$i]['href']='http://'.$_SERVER['HTTP_HOST'].'/'.$maps['routes'][$i]['request'].'/';
                    $requests[$i]['name']=  \Core\Utils::string_encode_camelCase($maps['routes'][$i]['request']);
                }
                $assets['link']=$requests;
                $assets=json_encode($assets,JSON_PRETTY_PRINT); 
//                $assets= \Core\Utils::json_format($assets);
                $this->write_assets($assets);
            }
            else {
                throw new \Exception('Maps config file is empty, doesn\'t exist or is corrupted');
            }
        } catch (\Exception $e) {
            echo $E->getMessage();
            die();
        }

        
    }
    
    private function generate_img_assets($path=NULL){            
                try{
                    if(is_dir(dirname(__FILE__).$this->pathToImgs)){
                        if($path===NULL){                            
                            $files=scandir(dirname(__FILE__).$this->pathToImgs);
                        }
                        else {                            
                            $files=scandir(dirname(__FILE__).$this->pathToImgs.$path);
                        }
                    }
                    else {
                        if($path===NULL){
                            throw new \Exception('"img" folder doesn\'t exist');
                        }
                        else {
                            throw new \Exception('One of "img" subfolders doesn\'t exist');
                        }                        
                    }
                } catch (\Exception $E) {
                    echo $E->getMessage();
                    die();
                }
            
                
                $src=array();
                if(is_array($files)&&!empty($files)){                    
                    foreach($files as $key=>$file){
                        if($file!=='..'&&$file!=='.'){
                            if(!is_dir(dirname(__FILE__).$this->pathToImgs.$file)){                                
                                $src[$key]['src']='assets/'.$file;
                                $name=  explode('.', $file);
                                $src[$key]['name']= \Core\Utils::string_encode_camelCase($name[0]);
                            }
                            else {                                
                                $img_assets=$this->generate_img_assets($file);   
                                if(is_array($img_assets)){
                                    foreach ($img_assets as $s){ 
                                        if($s!="null"){
                                            array_push($src, $s);
                                        }
                                    }
                                }
                                else {
                                    array_push($src, $img_assets);
                                }
                            }                            
                        }                        
                    }
                }                
            return $src;
    }
    
    /* creates js,css or images list based on the availible files */
    function generate_assets($type){
        switch($type){
            case 'img':
                try{
                    if(file_exists(dirname(__FILE__).$this->assets)){
                        $assets=  \Core\Utils::read_json(dirname(__FILE__).$this->assets); 
                    }
                    else {
                        throw new \Exception('assets file doesn\'t exist or is corrupted');
                    }
                } catch (\Exception $E) {
                    echo $E->getMessage();
                    die();
                }                
                $src=$this->generate_img_assets();
                $src=  array_values($src);
                $assets['img']=$src;                                 
                break;
            case ('js'||'css'):
                    if($type==='js'){
                        $files=  \Core\Files::r_scandir(dirname(__FILE__).$this->pathToJs);
                    }
                    else if($type==='css'){
                        $files=\Core\Files::r_scandir(dirname(__FILE__).$this->pathToCss);
                    }
                    $assets=  \Core\Utils::read_json(dirname(__FILE__).$this->assets);                     
                    if(is_array($files)&&!empty($files)){
                        if($type==='js'){
                            $filepath=$this->r_add_assets('js',$files);
                            $assets['js']=$filepath;
                        }
                        else if($type==='css'){
                            $filepath=$this->r_add_assets('css',$files);
                            $assets['css']=$filepath;
                        }
                    }
                    break;
        } 
        $assets=json_encode($assets,JSON_PRETTY_PRINT);
        $this->write_assets($assets);
    }  
    
    /* writes new assets into the file
     * accepts $assets json encoded as parameter
     */
    private function write_assets($assets){
        $handle=fopen(dirname(__FILE__).$this->assets, 'w');
        fwrite($handle, $assets);
    }
    
    private function r_add_assets($type,$files,$name_prefix=NULL){
        $filepath=array();        
        foreach($files as $key=>$file){
            if($file!=='..'&&$file!=='.'){
                if(!is_array($file)){ 
                    $ex_file=explode('.',$file);                    
                    if($type==='js'){
                        if($ex_file[1]==='js'){
                            $filepath[$key]['src']=  str_replace('/../..', '', $this->pathToJs).$file;
                        }
                    }
                    else if($type==='css'){
                        $filepath[$key]['src']=str_replace('/../..', '', $this->pathToCss).$file;
                    }                    
                    $name=  $ex_file[0]; 
                    if(($ex_file[1]==='js'&& $type==='js') || ($ex_file[1]==='css' && $type==='css')){
                        if($name_prefix===NULL){
                            $filepath[$key]['name']= \Core\Utils::string_encode_camelCase($name);
                        }
                        else {
                            $filepath[$key]['name']= \Core\Utils::string_encode_camelCase(\Core\Utils::string_encode_camelCase($name_prefix).ucfirst($name));
                        }  
                    }
                }
                else {
                    $name_prefix=$key;
                    $arr=$this->r_add_assets($type,$file,$name_prefix);                   
                    $filepath=array_merge($filepath,$arr);
                }
            }
        }
        return $filepath;
    }
    
}

?>
