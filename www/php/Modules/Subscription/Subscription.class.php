<?php
namespace Modules\Subscription;
class Subscription extends Definitions{
    public $apiKey="acf27898a156cd06552552d7f6760b90-us10";
    public $listId="";
    
    function subscribe(){
        if(\Core\Utils::is_ajax()){
            if(\Dev\Debug::is_dev_mode()){
                define('MAILCHIMP_DEV_MODE', true);
            }
            $MChimp=new \Mailchimp($this->apiKey,array('ssl_verifypeer' => false));
            $MChimpLists=new \Mailchimp_Lists($MChimp);
            $result=$MChimpLists->subscribe($this->listId, array("FNAME"=>$this->Kernel->Request->post['fName'],"lName"=>$this->Kernel->Request->post['lName'],"email"=>$this->Kernel->Request->post['email']));
            echo json_encode($result);
        }        
        else{
            if(\Dev\Debug::is_dev_mode()){
                define('MAILCHIMP_DEV_MODE', true);
            }
            $MChimp=new \Mailchimp($this->apiKey,array('ssl_verifypeer' => false));
            $MChimpLists=new \Mailchimp_Lists($MChimp);
            $result=$MChimpLists->subscribe($this->listId, array("FNAME"=>$this->Kernel->Request->post['fName'],"lName"=>$this->Kernel->Request->post['lName'],"email"=>$this->Kernel->Request->post['email']));
            return $result;
        } 
    }
    
    function update(){
        if(\Core\Utils::is_ajax()){
            if(\Dev\Debug::is_dev_mode()){
                define('MAILCHIMP_DEV_MODE', true);
            }
            $MChimp=new \Mailchimp($this->apiKey,array('ssl_verifypeer' => false));
            $MChimpLists=new \Mailchimp_Lists($MChimp);
            $result=$MChimpLists->subscribe($this->listId, array("euid"=>$this->Kernel->Request->post['euid']), array("FNAME"=>$this->Kernel->Request->post['fName'],"lName"=>$this->Kernel->Request->post['lName']), "html", true, true);
            echo json_encode($result);
        }
        else {
            if(\Dev\Debug::is_dev_mode()){
                define('MAILCHIMP_DEV_MODE', true);
            }
            $MChimp=new \Mailchimp($this->apiKey,array('ssl_verifypeer' => false));
            $MChimpLists=new \Mailchimp_Lists($MChimp);
            $result=$MChimpLists->subscribe($this->listId, array("euid"=>$this->Kernel->Request->post['euid']), array("FNAME"=>$this->Kernel->Request->post['fName'],"lName"=>$this->Kernel->Request->post['lName']), "html", true, true);
            return $result;
        }
    }
    
    
//    function unsubscribe($callData){
//        $MChimp=new \Mailchimp($this->apiKey,array('ssl_verifypeer' => false));
//        $result = $MChimp->call('lists/unsubscribe', $callData);
//        return $result;
//    }
    
}

?>
