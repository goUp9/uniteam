<?php
namespace Core;
/*
 * @package: core
 * @author: Anastasia Sitnina
 * @version: 1.0.0 
 * 
 * Utility functions
 */
class Utils{
    
    #get timezones difference
    public static function get_timezone_difference(){
        $LondonDateTimeZone = new \DateTimeZone('Europe/London');
        $LondonDateTime     = new \DateTime("now", $LondonDateTimeZone);

        $UtahDateTimeZone = new \DateTimeZone('America/Denver');
        $UtahDateTime     = new \DateTime("now", $UtahDateTimeZone);

        return $UtahDateTimeZone->getOffset($LondonDateTime) - $LondonDateTimeZone->getOffset($LondonDateTime);
    }
    
    #read json from file into an array
    public static function read_json($file_path){
        try {
           if(is_file($file_path)){
                $handle=fopen($file_path, 'r');        
                $json=fread($handle, filesize($file_path));
                $array=  json_decode($json, true); 
            }
            else {
                throw new \Dev\Exceptions\SystemException("The json file '".$file_path."' is missing",1001);
            }
        } catch (\Dev\Exceptions\SystemException $E) {
            $E->kill_process();
        }
        return $array;
    }
    
    #read json from file into an array recursive
    public static function rread_json($dir){
           $content = array();
           $cdir = scandir($dir); 
           foreach ($cdir as $value){  
              if (!in_array($value,array(".",".."))){ 
                 if (is_dir($dir.DIRECTORY_SEPARATOR.$value)){
                    $c = self::rread_json($dir.DIRECTORY_SEPARATOR.$value);                     
                    array_push($content, $c);
                 } 
                 else {
                    $c = self::read_json($dir.DIRECTORY_SEPARATOR.$value); 
                    array_push($content, $c);
                 } 
              } 
           }
        return $content;
    }
    
    /* check if request came via ajax */
    public static function is_ajax(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
    /* 
     * pagination function
     * @var int $numberOfItems - total amount of items
     * @var int $itemsPerPage - how many items should be displayed per page
     * @var int $currentPageNumber - current page 
     */    
    public static function pagination($numberOfItems, $itemsPerPage, $currentPageNumber=NULL){
        # if isset page number
        if ($currentPageNumber!=NULL){
            $offset=($currentPageNumber-1)*$itemsPerPage;
        }
        # start at the first page if no page number
        else {
            $offset=NULL;
            $currentPageNumber=1;
        }
        
        #What's the total number of pages?
        $numPages=ceil($numberOfItems/$itemsPerPage);
        
        return array('offset'=>$offset, 'totalPages'=>$numPages);
    }
    
    #LEGACY
    public static function filter_POST($post_name){        
        $var=  filter_var($_POST[$post_name]);
        return $var;
    }
    
    
    public static function filter_input($array,$flag=NULL){
        #$flag = e.g. FILTER_SANITIZE_SPECIAL_CHARS
        $clean=array();
        foreach ($array as $key=>$value){
            if(!is_array($value)){
                if ($flag!=NULL){
                    $clean[$key]=filter_var($value,$flag);
                }
                else {
                    $clean[$key]=filter_var($value);
                }
            }
            else {
                if ($flag!=NULL){
                    $clean[$key]=self::filter_input($value,$flag);
                }
                else {
                    $clean[$key]=self::filter_input($value);
                }
            }
        }
        return $clean;
    }
    
    public static function xml_to_array( $xmlObject, $out = array () ){
        foreach ( (array)$xmlObject as $index => $node ){
            if(is_object($node) || is_array($node) ){
                $out[$index]=  Utils::xml_to_array($node);
            }
            else {
                $out[$index]=$node;
            }
        }
        return $out;
    }
    
    public static function return_ini_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
    
    public static function encode_password($password){
//        switch to password_hash when PHP 5.5 will be installed
        $password=md5($password);
        return $password;
    }
    
    public static function string_encode_camelCase($string){
        $arr=  explode(' ', $string);
        $string='';
        foreach($arr as $key=>$s){
            if($key!=0){
                $s=ucfirst($s);
            }
            $string.=$s;
        }
        $arr=  explode('_', $string);
        $string='';
        foreach($arr as $key=>$s){
            if($key!=0){
                $s=ucfirst($s);
            }
            $string.=$s;
        }
        $arr=  explode('-', $string);
        $string='';
        foreach($arr as $key=>$s){
            if($key!=0){
                $s=ucfirst($s);
            }
            $string.=$s;
        }
        return $string;
    }
    
    public static function string_decode_camelCase($string){
         return preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$string);
    }
    
    public static function is_empty_dir($dir){
        $handle=opendir($dir);
        while(false!=  ($entry=readdir($handle))){
            if($entry!='.'&&$entry!='..'){
                return FALSE;
            }
        }
        return TRUE;
    }
    
    public static function import_mysql($file_path,$entityManager){
        $dump=file($file_path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        
        $stmts=array();
        $sqlCombine='';
        foreach($dump as $sql){
            if(strpos($sql, '--')!==0 && strpos($sql, '/*')!==0){
                if(strpos($sql,';')===  strlen($sql)-1){
                    $sqlCombine.=$sql;
                    array_push($stmts, $sqlCombine);
                    $sqlCombine='';
                }
                else {
                    $sqlCombine.=$sql;
                }
            }
        }
        foreach($stmts as $s){
            $stmt = $entityManager->getConnection()->prepare($s);
            $stmt->execute();
        }
        
    }
    
    public static function load_xml($filePath){
        $xml=simplexml_load_file($filePath);
        return $xml;
    }
    
    public static function save_xml($filePath, $SimpleXmlObject){
        file_put_contents($filePath, $SimpleXmlObject->asXML());        
        return $this;
    }
    
    public static function generate_random_name(){
        return md5(rand(1000,9999));
    }
    
    public static function generate_current_timestamp(){
        $DT=new \DateTime(date('Y-m-d H:i:s'));
        $DT->setTimezone(new \DateTimeZone("Europe/London"));
        return $DT;
    }
    
    public static function is_assoc(array $array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
    
    public static function generate_random_string($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

?>
