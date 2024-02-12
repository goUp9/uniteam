<?php
namespace Dev;
class Compile {
    # maps
    public $mapsPath='config/maps/';    
    public $map='config/maps.json';
    
    #assets
    public $assetsPath='config/assets/';
    public $assets='config/assets.json';
       
    function compile_maps(){
        $content=\Core\Utils::rread_json($this->mapsPath);
        $rts=array();
        foreach($content as $set){
            foreach ($set as $item){
                array_push($rts,$item['route']);
            }
        }
        $routes['routes']=$rts;
        $map=json_encode($routes,JSON_PRETTY_PRINT);
        
        #write file
        $handle=fopen($this->map, 'w');
        fwrite($handle, $map);
        return $this;
    }
    
    function compile_assets(){
        $content=\Core\Utils::rread_json($this->assetsPath);        
        $asts=array();
        foreach($content as $set){
            foreach ($set[0] as $key=>$item){
                if(!isset($asts[$key])){
                    $asts[$key]=array();
                }
                $asts[$key]=array_merge($asts[$key],$set[0][$key]);
            }
        }
        
        $assets=json_encode($asts,JSON_PRETTY_PRINT);
        
        #write file
        $handle=fopen($this->assets, 'w');
        fwrite($handle, $assets);
        return $this;
    }
    
    function compile_internal_links(){
        $content=\Core\Utils::rread_json($this->mapsPath);
        
        $requests=array();
        foreach($content as $set){
            foreach($set as $item){
                $link=array(
                    'href'=>$item['route']['request'].'/',
                    'name'=> str_replace('/','__',str_replace('-', '_', $item['route']['request']))
                );
                array_push($requests, $link);
            }
        }
        
        $assetsDoc=\Core\Utils::read_json($this->assets);
        $assets=array();
        $assets['img']=$assetsDoc['img'];
        $assets['path']=$assetsDoc['path'];
        if(isset($assetsDoc['email'])){
            $assets['email']=$assetsDoc['email'];
        }
        $assets['link']=  array_merge($assetsDoc['link'],$requests);
        
        $assets=json_encode($assets,JSON_PRETTY_PRINT);
        
        #write file
        $handle=fopen($this->assets, 'w');
        fwrite($handle, $assets);
        return $this;
    }
    
    function combine_js(){
        $content=\Core\Utils::rread_json($this->mapsPath);
        $combined='';
        foreach($content as $set){
            foreach ($set as $item){ 
               foreach($item['route']['js'] as $jsFile){                     
                    $jsFile=  substr($jsFile, 1);
                    if(file_exists($_SERVER['DOCUMENT_ROOT'].$jsFile)){
                        $handle=fopen($_SERVER['DOCUMENT_ROOT'].$jsFile, 'r');
                        $jsContent=fread($handle, filesize($_SERVER['DOCUMENT_ROOT'].$jsFile));
                        $combined.=$jsContent.'
                                ';
                    }
                    else {
                        \Core\Errorshandler::to_log('Error while combining JS files - file doesn\'t exist: '.$_SERVER['DOCUMENT_ROOT'].$jsFile);
                    }
               }
               
            if($combined!==''){
                $this->write_combined_js($item, $combined);
               }
            }
        }        
    }
    
    private function write_combined_js($item,$combined){
        //$fileName= str_replace('/','__',str_replace('-', '_', $item['route']['request']));
        $pathArr=explode('/',$item['route']['request']);
        $path='';       
        if(is_array($pathArr)){
            $fileName=array_pop($pathArr);  
            $fileName= str_replace('-', '_', $fileName);
            foreach($pathArr as $p){
                $path.=$p.'/';
            }
        }
        else {
            $fileName= str_replace('-', '_', $item['route']['request']);
        }
        
        if($path!==''){
            if(!is_dir(DEPLOYMENT_PATH.'js/'.$path)){
                mkdir(DEPLOYMENT_PATH.'js/'.$path);
            }
        }

        $handle=fopen(DEPLOYMENT_PATH.'js/'.$path.$fileName.'.js', 'w+');        
        fwrite($handle, $combined);
        $combined='';
    }
}
