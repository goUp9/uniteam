<?php
namespace Bundle\Controllers\Users;
class Registration extends \Modules\Users\Registration{
    public $repository=array("Users","UINQuery");
    
    private $fields=array(
        "username",
        "password",
        "password2",
        "fName",
        "lName",
        "email",
//        "mobile",
//        "address",
//        "city",
//        "country",
//        "zip"
    );
    
    private $unique_fields=array(
        "username",
        "email"
    );
    
    function main(){
        $Login=new \Modules\Users\Login($this->Kernel);
        $Commons=new \Bundle\Controllers\Commons\Users($this->Kernel);
        $Commons->remember_user();        
        if($Login->is_logged()){
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','myuin__personal')['href']);
        }
        
        return $this->Kernel;
    }
    
    function register_with_google_plus(){
        $response = json_decode(file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$this->Kernel->Request->post['id_token']),TRUE);
        $User=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneByGooglePlusId($response['sub']);
        
        if(!is_object($User)){
            $User=new \Bundle\Doctrine\Entities\Users();
            $User->setGooglePlusId($response['sub']);
            if(isset($response['name'])){
                $User->setUsername($response['name']);
            }
            if(isset($response['email'])){
                $User->setEmail($response['email']);
            }
            if(isset($response['given_name'])){
                $User->setFName($response['given_name']);
            }
            if(isset($response['family_name'])){
                $User->setLName($response['family_name']);
            }
            $User->setStatus(TRUE);
            $this->Kernel->entityManager->persist($User);
            $this->Kernel->entityManager->flush();
            
            $_SESSION['user']['id']=$User->getId();
            if($User->getNewsletterSubscribed()===NULL){
                $_SESSION['promtNewsletter']=TRUE;
            }
            $replacementArray=[
                'user'=>$User->getUsername(),
                'domain'=>"http://".$_SERVER['HTTP_HOST']
            ];
            
            new \Modules\EmailNotifications\EmailNotification($this->Kernel, new \PHPMailer, $User->getEmail(), 13, $replacementArray, TRUE);
            header("location:http://".$_SERVER['HTTP_HOST']);
        }
        else{
            $_SESSION['user']['id']=$User->getId();
            if($User->getNewsletterSubscribed()===NULL){
                $_SESSION['promtNewsletter']=TRUE;
            }
            header("location:http://".$_SERVER['HTTP_HOST']);
        }
        
    }
    
    public function login_w_facebook(){
        if($this->Kernel->Request->post['userID']){
//            parent::user_exists($this->Kernel->entityManager, $this->get_repository()[0], $this->Kernel->Http->request->get('userID'));
            $User=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneByFacebookId($this->Kernel->Request->post['userID']);
            if(is_object($User)){
                $_SESSION['user']['id']=$User->getId();
                if($User->getNewsletterSubscribed()===NULL){
                    $_SESSION['promtNewsletter']=TRUE;
                }
                header("Refresh:0");
                echo 1;
            }   
            else{
                echo 0;
            }
        }
    }


    public function register_w_facebook(){
        if(isset($this->Kernel->Request->post)&&!empty($this->Kernel->Request->post)){
            $User=new \Bundle\Doctrine\Entities\Users();
            $userData=$this->Kernel->Request->post;
//            \Dev\Debug::dump($userData);
            $User->setFacebookId($userData['id']);
            $User->setUsername($userData['first_name'].'_'.$userData['last_name']);
            $User->setEmail($userData['email']);
            $User->setFName($userData['first_name']);
            $User->setLName($userData['last_name']);
            $User->setStatus(TRUE);
            $this->Kernel->entityManager->persist($User);
            $this->Kernel->entityManager->flush();
            $_SESSION['user']['id']=$User->getId();
            $replacementArray=[
                'user'=>$User->getUsername(),
                'domain'=>"http://".$_SERVER['HTTP_HOST']
            ];
            new \Modules\EmailNotifications\EmailNotification($this->Kernel, new \PHPMailer, $User->getEmail(), 13, $replacementArray, TRUE);
            if($User->getNewsletterSubscribed()===NULL){
                $_SESSION['promtNewsletter']=TRUE;
            }
            header("Refresh:0");
        }
    }
    
    public function register_with_twitter(){
        $consumerKey='tCLxIR10jAMVcSjRhVOyeFvMs';
        $consumerSecret='KeyFIydFDYaDUypiMnOMF8tX4UWMVsDVziLnbxeSguk1DSHaV7';
        function getConnectionWithAccessToken($oauth_token, $oauth_token_secret,$consumerKey, $consumerSecret) {
            $connection = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $oauth_token, $oauth_token_secret);
            return $connection;
        }

        $connection = getConnectionWithAccessToken("3019464324-FQbi3KxFjhQu3Lm4lW9jN6LjWdphQSEYGLJU1xu", "Gmdh12QRK8udlVkTLvStPiQ1lcLziqF2wlxMJLraXrwCo",$consumerKey, $consumerSecret);
        $oauth_token = $connection->oauth("oauth/request_token");
        $url = $connection->url("oauth/authorize", ["oauth_token" => $oauth_token['oauth_token']]);
        echo $url;
    }
    
    private function set_query_for_new_user($UserEntry){
        $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($_SESSION['currentQuery']);
        if(is_object($Query)){
            $Query->setIdUser($UserEntry);
            $this->Kernel->entityManager->persist($Query);
            $this->Kernel->entityManager->flush();
            unset($_SESSION['currentQuery']);
        }
    }
    
    public function twitter_registration_success(){
        if($this->Kernel->Request->get['oauth_token'] && $this->Kernel->Request->get['oauth_verifier']){
            
            $consumerKey='tCLxIR10jAMVcSjRhVOyeFvMs';
            $consumerSecret='KeyFIydFDYaDUypiMnOMF8tX4UWMVsDVziLnbxeSguk1DSHaV7';
            function getConnectionWithAccessToken($oauth_token, $oauth_token_secret,$consumerKey, $consumerSecret) {
                $connection = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $oauth_token, $oauth_token_secret);
                return $connection;
            }

            $connection = getConnectionWithAccessToken($this->Kernel->Request->get['oauth_token'], $this->Kernel->Request->get['oauth_verifier'],$consumerKey, $consumerSecret);
            
            $tokens = $connection->oauth("oauth/access_token", ["oauth_token"=>$this->Kernel->Request->get['oauth_token'],"oauth_verifier"=>$this->Kernel->Request->get['oauth_verifier']]);
            $connection = getConnectionWithAccessToken($tokens['oauth_token'], $tokens['oauth_token_secret'],$consumerKey, $consumerSecret);
            
            $response=(array)$connection->get('account/verify_credentials', ["include_email" =>"true"]);

            $User=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneByTwitterId($response['id']);
            if(!is_object($User)){
                $User=new \Bundle\Doctrine\Entities\Users();
                $User->setTwitterId($response['id']);
                if(isset($response['screen_name'])){
                    $User->setUsername($response['screen_name']);
                }
                if(isset($response['email'])){
                    $User->setEmail($response['email']);
                }
                
                if(isset($response['name'])){
                    $name=  explode(' ', $response['name']);
                    if(isset($name[0])){
                        $User->setFName($name[0]);
                    }
                    if(isset($name[1])){
                        $User->setLName($name[1]);
                    }
                }
                
                $User->setStatus(TRUE);
                
                $replacementArray=[
                    'user'=>$User->getUsername(),
                    'domain'=>"http://".$_SERVER['HTTP_HOST']
                ];
                new \Modules\EmailNotifications\EmailNotification($this->Kernel, new \PHPMailer, $User->getEmail(), 13, $replacementArray, TRUE);
                
                $this->Kernel->entityManager->persist($User);
                $this->Kernel->entityManager->flush();  

                $_SESSION['user']['id']=$User->getId();
                if($User->getNewsletterSubscribed()===NULL){
                    $_SESSION['promtNewsletter']=TRUE;
                }
                header("location:http://".$_SERVER['HTTP_HOST']);
            }
            else{
                $_SESSION['user']['id']=$User->getId();
                if($User->getNewsletterSubscribed()===NULL){
                    $_SESSION['promtNewsletter']=TRUE;
                }
                header("location:http://".$_SERVER['HTTP_HOST']);
            }
            
        }
    }
     
    function action_register(){
        $flagValid=TRUE;
        $unValidFields=array();
        foreach($this->fields as $field){
            $Validation=new \Modules\Users\Validation($this->Kernel);
            $vld=$Validation->form_field_exists($field);
            if($vld===FALSE){
                array_push($unValidFields, $field);
                $flagValid=FALSE; // required field is missing
            }
        }
        if($flagValid){
                $user_exists=$this->validate_user_exists($this->unique_fields);
                if(!$user_exists){ //if it's not already on the system - register            
                    $id=$this->register();
                    $entry = $this->Kernel->entityManager
                            ->getRepository($this->get_repository()[0])
                            ->findOneBy(array("id"=>$id));
                    $stringKey=md5($entry->getId().rand(0,15));
                    $entry->setVarificationEmail($stringKey);
                    $this->Kernel->entityManager->persist($entry);
                    $this->Kernel->entityManager->flush();
                    
                    if(isset($_SESSION['currentQuery'])&&!empty($_SESSION['currentQuery'])){
                        $this->set_query_for_new_user($entry);
                    }
                    
                    $link=$this->Kernel->Content->insert_asset('link','verify_email');
                    $replacementArray=array(
                        "link"=>LINKS_PRE.$link['href'].'?key='.$stringKey,
                        "user"=>$entry->getUsername(),
                        'domain'=>"http://".$_SERVER['HTTP_HOST']
                    );
                    new \Modules\EmailNotifications\EmailNotification($this->Kernel, new \PHPMailer, $entry->getEmail(), 4, $replacementArray, TRUE);
                    $AjaxResult=new \Core\AjaxResult(TRUE, '<p>Thank you! You will receive a confirmation email shortly.</p> <p>Please follow the link in the email to finalize your registration</p>');                    
                }
                else {
                    $fields='';
                    foreach($user_exists as $field){
                        $fields.=$field.' and ';
                    }
                    $msg="User with ".trim($fields,' and ')." provided already exists on our system.";
                    $AjaxResult=new \Core\AjaxResult(FALSE, $msg);
                }
        }
        else {
            $fields='';
            foreach($unValidFields as $field){
                $fields.=$field.' and ';
            }
            $msg=trim($fields," and ")." can't be empty.";
            $AjaxResult=new \Core\AjaxResult(FALSE, $msg);
        } 
        echo $AjaxResult->to_JSON();
    }
    
    function email_varification(){        
        $Login=new \Modules\Users\Login($this->Kernel);
        $Commons=new \Bundle\Controllers\Commons\Users($this->Kernel);
        $Commons->remember_user();
        if($Login->is_logged()){
            header('Location:'.LINKS_PRE.$this->Kernel->Content->insert_asset('link','myuin__personal')['href']);
        }
        
        $verified=$this->varify_email();
        $this->Kernel->Content->set_data($verified,"verified");
       
        return $this->Kernel;
    }
    
    
    
}
