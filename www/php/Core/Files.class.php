<?php
namespace Core;
/**
 * Description of files
 * @package:
 * @author:
 * @version:
 */
class Files {
    #flags
    const ALL=0;
    const STRICT=1;
    
    #max default file upload size
    const MAX_FILE_SIZE=9000000;
    
    public static function check_ext ($path_to_file, $ext, $flag=self::ALL){
        $extO=pathinfo($path_to_file, PATHINFO_EXTENSION);
        switch ($flag){            
            case 1:
                    $extO=  strtolower($extO);
                    $ext=  strtolower($ext);
                break;
        }
        return ( $ext===$extO ? true : false );
    }
    
    # copy all files from a folder
     public static function copy_all_files($path, $dest){
        if (is_dir($path)){
            $files=scandir($path);
            if (is_array($files)){
                foreach($files as $file){
                    if(!is_dir($path.$file)&& !is_dir($dest.$file)){
                        if ($file!='..' && $file!='.'){                    
                            copy($path.$file, $dest.$file);
                        }
                    }
                }
                return true;
            }
        }
        else {
            throw new \Exception($path, 1000);
        }
    }
    
    # read all files from a folder
     public static function read_all_files($path){
        $contents=array();
        if (is_dir($path)){
            $files=scandir($path);
            foreach($files as $file){
                if ($file!='..' && $file!='.'){
                    $file_h=fopen($path.$file, 'r');
                    $content=fread($file_h, filesize($path.$file));
                    $contents[$file]=$content;                    
                }
            }
            return $contents;
        }
        else {
            throw new Exception($path, 1000);
        }
    }
    
    # copy all files and folders
    public static function rcopy($src, $dst) {
        if (file_exists($dst)) $this->rrmdir($dst);
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file){
                if ($file != "." && $file != "..") self::rcopy("$src/$file", "$dst/$file");
            }
        }
        else if (file_exists($src)) copy($src, $dst);
    }
    
    #remove all files and folders
    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
            if ($file != "." && $file != "..") self::rrmdir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }
    
    public static function r_scandir($dir){   
           $files = array();
           $cdir = scandir($dir); 
           foreach ($cdir as $value){ 
              if (!in_array($value,array(".",".."))){ 
                 if (is_dir($dir.DIRECTORY_SEPARATOR.$value)) 
                 { 
                    $files[$value] = self::r_scandir($dir.DIRECTORY_SEPARATOR.$value); 
                 } 
                 else 
                 { 
                    $files[] = $value; 
                 } 
              } 
           }
           return $files; 
    }
    
    /* check the mime type of the file */
    public static function check_mime($file_mime, $valid_mimes){        
        $flag=false;
        foreach ($valid_mimes as $mime){                            
            if ($file_mime==$mime){
                $flag=true;
            }
        }
        return $flag;
    }
    
    
    public static function upload_files($input_name, $path_to_save_to, $valid_mimes=NULL, $max_file_size=self::MAX_FILE_SIZE){
        if (isset($_FILES[$input_name]) && !empty($_FILES[$input_name])){
            # going through all uploaded files 
            for ($i=0;$i<count($_FILES[$input_name]['name']);$i++){
                # checking for uploading error
                if ($_FILES[$input_name]['error'][$i]==0){
                    if ($valid_mimes!==NULL){
                        if (is_array($valid_mimes)){
                            $file_mime=$_FILES[$input_name]['type'][$i];
                            #checking mime types
                            $flag=self::check_mime($file_mime, $valid_mimes);
                        }                    
                    }
                    else {
                        $flag=true;
                    }   
                    
                    if ($_FILES[$input_name]['size'][$i]<$max_file_size){
                           $tmp_file=$_FILES[$input_name]['tmp_name'][$i];        
                           $nameO=$_FILES[$input_name]['name'][$i]; 
                           $fileData=self::save_file($tmp_file, $nameO, $path_to_save_to);
                    }
                }
                else {
                    $fileData[$i]=array('error'=>$_FILES[$input_name]['error'][$i]);
                }
            }
        }
        return $fileData;  
    }
    
    public static function upload_file($input_name, $path_to_save_to, $valid_mimes=NULL, $max_file_size=self::MAX_FILE_SIZE){
        if (isset($_FILES[$input_name]) && !empty($_FILES[$input_name])){            
                # checking for uploading error
                if ($_FILES[$input_name]['error']==0){
                    if ($valid_mimes!==NULL){
                        if (is_array($valid_mimes)){
                            $file_mime=$_FILES[$input_name]['type'];
                            #checking mime types
                            $flag=self::check_mime($file_mime, $valid_mimes);
                        }                    
                    }
                    else {
                        $flag=true;
                    }   
                    
                    if ($_FILES[$input_name]['size']<$max_file_size){
                           $tmp_file=$_FILES[$input_name]['tmp_name'];        
                           $nameO=$_FILES[$input_name]['name'];                            
                           $fileData=self::save_file($tmp_file, $nameO, $path_to_save_to);
                    }
                }
                else {
                    $fileData=array('error'=>$_FILES[$input_name]['error']);
                }            
        }
        return $fileData;  
    }
    
    private static function save_file($tmpFile, $nameOriginal, $pathToSaveTo, $name=NULL){
        $file=explode(".", $nameOriginal);        
        # if name wasn't given - create a random name
        if ($name===NULL){
            $name=md5(rand(1000,9999)); 
        }
        $ext=  strtolower($file[1]);                    

        $path=$_SERVER['DOCUMENT_ROOT'].$pathToSaveTo.$name.".".$ext;

        move_uploaded_file($tmpFile, $path);

        $fileData=array("path"=>$path,"name"=>$name,"ext"=>$ext);
        return $fileData;
    }
    
}

?>
