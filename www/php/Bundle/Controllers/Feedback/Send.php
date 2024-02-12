<?php
namespace Bundle\Controllers\Feedback;
class Send extends \Modules\Modules{
    
    function main(){
        if(\Core\Utils::is_ajax()){
            $PHPMailer=new \PHPMailer();
            $PHPMailer->addAddress("info@uinteam.com");
            $PHPMailer->Body='A new feedback message from:'.$this->Kernel->Request->post['email'].'. '.PHP_EOL.'Message: '.$this->Kernel->Request->post['msg'];
            echo $PHPMailer->send();
        }
    }
    
}
