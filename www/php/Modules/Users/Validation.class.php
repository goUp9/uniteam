<?php
namespace Modules\Users;
/*
 * Description of validation_class
 * Handles data validation
 * @package: USERS
 * @author: Fairy-Wilbury
 * @version: 1.0.0
 */
class Validation extends \Modules\Modules{
    
    /*reg expressions to check against*/
    public static $regexps=array(
        'number' => "^[-]?[0-9,]+\$",
        'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
        'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
        'email' => "/[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}/",
        'username' => "/[^(\w)|(\s)]/",
    );
    
    # password = all letters or numbers only
    public static function validate($data, $flag){ 
        switch ($flag) {
            case 'password':
                    $vld=  ctype_alnum($data);
                break;
            default:
                    $vld=   preg_match(self::$regexps[$flag],$data);
                break;
        }
        return $vld;
    }
    
    public static function check_length($data, $min, $max=NULL){        
        return (strlen($data)>$min && (strlen($data)>$max || $max==NULL)) ? true : false;        
    }
    
    public function form_field_exists($field_name){
        if(isset($this->Kernel->Request->post[$field_name]) && !empty($this->Kernel->Request->post[$field_name])) {
            return true;
        }
        else {
            return false;
        }
    }
    
}

?>
