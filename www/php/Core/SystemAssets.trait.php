<?php
namespace Core;
/**
 * Holds the tools functions to check and make System Assets
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
trait SystemAssets {
    
    /* 
     * identifies if the asset was described as a relative path
     * or an outside link
     * @param (string) $path
     * @return (bool) - TRUE if the path is relative, FALSE for an absolute path
    */
    public static function is_localPath($path){
        if(strpos($path, 'http://')===FALSE && strpos($path, 'http:\/\/')===FALSE && strpos($path, 'https://')===FALSE && strpos($path, 'https:\/\/')===FALSE){
            return true;             
        }
        else {
            return false;
        }
    }
    
    /* 
     * identifies if the email address is local (on current host)
     * or on a different host
     * @param (string) $emailAddess - email address defined in Assets
     * @return (bool) - TRUE is it is an email on this host, FALSE - if the host is defined within the email address
     */
    public static function is_localEmail($emailAddress){
        if(strpos($emailAddress, '@')===FALSE){
            return true;
        }
        else {
            return false;
        }
    }
    
    
    /* 
     *  RAW TO VALID ASSET MAKERS 
     * 
     */
    
    /*
     * makes an image asset
     * @param (array) $img - raw image asset
     * @return (array) $img - valid image asset
     */
    protected function make_imgAsset($img){
        if($this->is_localPath($img['src'])){
            $img['src']=self::make_localUrl($img["src"]);
        }
        $img['tag']='<img src="'.$img['src'].'" alt="'.$img['alt'].'"/>';
        return $img;
    }
    
    /*
     * makes an email asset
     * @param (array) $email - raw email asset
     * @return (array) $email - valid email asset
     */
    protected function make_emailAsset($email){
        if($this->is_localEmail($email['address'])){
            $email['address']=self::make_localEmail($email['address']);
        }
        return $email;
    }
    
    /*
     * makes a path asset
     * @param (array) $path - raw path asset
     * @return (array) $path - valid path asset
     */
    protected function make_pathAsset($path){
        if(self::is_localPath($path['href'])){
            $path['href']=self::make_localUrl($path['href']);
        }
        return $path;
    }
    
    /*
     * makes a link asset
     * @param (array) $link - raw link asset
     * @return (array) $link - valid link asset
     */
    protected function make_linkAsset($link){
        if(self::is_localPath($link['href'])){
            $link['href']=self::make_localUrl($link['href']);
        }
        return $link;
    }
    
    /*
     * makes a css asset
     * @param (array) $css - raw css asset
     * @return (array) $css - valid css asset
     */
    protected function make_cssAsset($css){
        if(self::is_localPath($css)){
            $css=self::make_localCss($css);
        }
        $css='<link rel="stylesheet" type="text/css" href="'.$css.'"/>';         
        return $css;
    }
    
    /*
     * makes a js asset for development mode
     * @param (array) $js - raw js asset
     * @return (array) $js - valid js asset
     */
    protected function make_jsDevAsset($js){
        if(self::is_localPath($js)){
            $js=self::make_localJs($js);
        }
        $js='<script src="'.$js.'" type="text/javascript"></script>';         
        return $js;
    }
    
    /* makes a js asset
     * @param (array) $map - current page map
     * @return (array) $js - valid js asset
     */
    protected function make_jsAsset($map){
        $host=str_replace('www.', '', $_SERVER['HTTP_HOST']);        
        $jsFile=  str_replace('__','/',str_replace('-', '_', $this->Kernel->Request->route['full']));
        $js='<script src="http://'.$host.'/'.DEPLOYMENT_PATH.'js/'.$jsFile.'.js" type="text/javascript"></script>';         
        return $js;
    }
    
    protected function make_keywords($map){
        $keywords='';
        if(isset($map['keywords'])&&!empty($map['keywords'])){            
            foreach($map['keywords'] as $keyword){
                $keywords.=$keyword.',';                        
            }
            $keywords=rtrim($keywords, ',');
        }
        return $keywords;
    }
    
    
    /*
     * makes a link with a given path on the local server
     * @param (string) $path - the path to appends
     * @return (string) $src - url 
     */
    private static function make_localUrl($path){
        $url='http://'.$_SERVER['HTTP_HOST'].'/'.$path;
        return $url;
    }
    
    /*
     * makes an email address with a given name on the current host
     * @param (string) $emailAddress
     * @return (string) $email - email address on the current host
     */
    private static function make_localEmail($emailAddress){
        $host=  str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $email=$emailAddress.'@'.$host;
        return $email;
    }
    
    /*
     * makes a local css asset
     * @param (string) $css
     * @return (string) $css
     */
    private static function make_localCss($css){
        $host=str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $css='http://'.$host.'/'.$css;    
        return $css;
    }
    
    /*
     * makes a local js asset
     * @param (string) $js
     * @return (string) $js
     */
    private static function make_localJs($js){
        $host=str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $js='http://'.$host.$js;    
        return $js;
    }
    
}


?>
