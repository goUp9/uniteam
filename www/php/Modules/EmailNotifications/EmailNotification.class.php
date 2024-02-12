<?php
namespace Modules\EmailNotifications;
class EmailNotification extends \Modules\Modules{
    public $repository="EmailNotificationTemplates";
    
    public $Mailer;
    
    /* 
     * @var $Kernel (\Core\Kernel)
     * @var $PhpMailer (\PHPMailer)
     * 
     */    
    public function __construct(\Core\Kernel $Kernel, \PHPMailer $PhpMailer,$to,$idTemplate, $replacementArray=array(),$is_SMTP=FALSE) {
        $this->Kernel=$Kernel;
        $this->Mailer=$PhpMailer;
		
        $entry=$this->get_template_by_id($idTemplate);
        if($entry!==NULL){
            $body=$entry->getBody();
			$this->Mailer->addAddress($to);
            $this->Mailer->isHTML(true);
			$this->Mailer->From='info@uinteam.com';
            $this->Mailer->FromName='UinTeam';
            $this->Mailer->Sender='info@uinteam.com';
            $this->Mailer->Subject=$entry->getSubject();
            $this->Mailer->Body=  $this->replace_in_template($body, $replacementArray);
            $sent=$this->send($to,$is_SMTP);
			
            //echo $sent;
            return $sent;
        }
        else {
            return FALSE;
        }
    }
    
    public function get_template_by_id($idTemplate){
        $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array("id"=>$idTemplate));
        if(!is_object($entry)){
            $entry=NULL;
        }
        return $entry;
    }
    
    private function send($to,$is_SMTP){        
        if($is_SMTP){
            $this->set_SMTP();
        }
        
        $this->Mailer->isHTML(TRUE);
        
        $this->Mailer->addAddress($to);
        $sent=$this->Mailer->send();
        return $sent;
    }
    
    private function set_SMTP(){
        $smtp=\Core\Utils::read_json($_SERVER['DOCUMENT_ROOT'].'/'.SMTP_PATH);
        //if(\Dev\Debug::is_dev_mode()){
            //$this->Mailer->SMTPDebug=2;
        //}
        $this->Mailer->isSMTP(TRUE);
        $this->Mailer->SMTPAuth   = true; 
        $this->Mailer->SMTPSecure = "ssl";
        $this->Mailer->SMTPAuth=true;  
        $this->Mailer->Host=$smtp['host'];
        $this->Mailer->Port=$smtp['port'];
        $this->Mailer->Username=$smtp['username'];
        $this->Mailer->Password=$smtp['password'];
    }
    
    private function replace_in_template($template, $replacementArray){    
        $loader=new \Twig_Loader_String();
        
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
        
        return $twig->render($template, $replacementArray);        
    }
}
