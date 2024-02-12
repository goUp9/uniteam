<?php
namespace Core;
/* 
 * Errors handling class
 * @package: core
 * @author: Anastasia Sitnina
 * @version: 1.0.0 
 */
class Errorshandler extends \Exception{
    
    public static function to_log($logdata){        
        if (is_array($logdata)) {
            foreach ($logdata as $key=>$ld){
                $str.=$key.'=>'.$ld.'\n';
            }
            $logdata=$str;
        }        
        $file_h=fopen($_SERVER['DOCUMENT_ROOT'].'/'.DEV_LOG_PATH.date('H-i-s_d-M-Y').'.txt', 'w');        
        fwrite($file_h, $logdata);
    }
    
    function all_errors($e){
        
        switch ($e->getCode()) {
            #general errors
            case 100:
                $error="#100 error. A technical error has occurred. We apologize for any inconveniences.";
                break;            
            #db errors
//            case 0:
//                $error='#0 error Mysql Database returned an error: '.$e->getMessage();
//                break;
            #installer errors
            # 1000 - 1100
//            case 1000:
//                $error='#1000 error. Installation Data is missing: '.$e->getMessage().'! Installation aborted';
//                break;
//            case 1001:
//                $error='#1001 error. Unable to create directory '.$e->getMessage().'! Installation aborted';
//                break;
//            case 1002:
//                $error='#1002 error. Unable to create file '.$e->getMessage().'! Installation aborted';
//                break;
            #auth errors
            #1100 - 1200
            case 1100:
                $error='#1100 error. Incorrect username and/or password';
                break;
            case 1101:
                $error='#1101 error. Invalid login info in the Session. Logged out anyways.';
                break;
            case 1102:
                $error="#1102 error. Password does not match the password confirmation.";
                break;
            case 1103:
                $error="#1103 error. The activation link is invalid. Maybe the account has already been activated. If you are unable to login - please contact the administrator.";
                break;
            #form errors
            #1200-1300
            case 1200:
                $error='#1200 error. This field is required: '.$e->getMessage();
                break;
            case 1201:
                $error='#1200 error. Invalid data submitted to: '.$e->getMessage().' field';
                break;
            #email errors
            #1300-1400
            case 1300:
                $error='#1300. Email wasn\'t sent';
        }  
        return $error;
    }
}

?>
