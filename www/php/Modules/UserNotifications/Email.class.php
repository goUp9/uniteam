<?php
namespace Modules\UserNotifications;
class Email {
    
    public $Mailer;
    
    public function __construct($Recipient, $subject, $templateName, $replacementArray) {
        $this->Mailer=new \PHPMailer();
        $this->Mailer->isHTML();
        $this->set_SMTP();
        $this->Mailer->Subject=$subject;
        $this->Mailer->setFrom('system@uinteam.com', 'uinteam');
        $this->Mailer->Body=  $this->replace_in_template($templateName, $replacementArray); 
        $this->Mailer->addAddress($Recipient->getEmail());       
    }
    
    protected function set_SMTP(){
        $this->Mailer->isSMTP(TRUE);
        $this->Mailer->SMTPAuth   = true; 
        $this->Mailer->SMTPSecure = "ssl";
        $this->Mailer->Username = "system@uinteam.com";
        $this->Mailer->Password = "eC+rIha7lZ";
        $this->Mailer->Port = 465;
        $this->Mailer->Host='box867.bluehost.com';
    }


    public function send(){
        $sent=$this->Mailer->send();      
        return $sent;
    }
    
    
    private static function replace_in_template($templateName, $replacementArray){    
        $loader=new \Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/templates/website/emailNotifications/');
        
        if(isset($_SESSION['development_mode'])&&$_SESSION['development_mode']){
            $twig=new \Twig_Environment($loader,array( 
                    'debug'=>true, 
                    'charset'=>'utf-8'
            ));
            $twig->addExtension(new \Twig_Extension_Debug());
        }
        else {
            $cache=$_SERVER['DOCUMENT_ROOT'].'/cache/';
            $twig=new \Twig_Environment($loader,array(
                    'cache'=>$cache,
                    'autoreload'=>true,
                    'charset'=>'utf-8'
            )); 
        }
        
        return $twig->render($templateName, $replacementArray);        
    }
    
}
