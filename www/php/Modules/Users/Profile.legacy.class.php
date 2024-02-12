<?php
namespace Modules\Users;
class Profile extends \Modules\Modules{
    public $repository="users";
    
    function unlogged(){
        if(!\Bundle\Controllers\Index\Login::is_logged()){
            #create content object
            $Content=new \Core\ContentFactory();
            $Content->init_assets();

            #create meta object
            $Meta=new \Core\MetadataFactory();
            $Meta->setTitle('User Profile');
            $Meta->setKeywords('');
            $Meta->setDescription('');
            $Meta->add_assets();

            /* Login */
            $Forms=new \Bundle\Controllers\Index\Forms();
            $loginForm=$Forms->login();

            $Content->set_form($loginForm, 'profile');

            $this->Kernel->Response->template='login.html.twig';

            return $this->Kernel;
        }
    }
    
    function general(){
        $this->unlogged();
        #create content object
        $Content=new \Core\ContentFactory();
        $Content->init_assets();
        
        #create meta object
        $Meta=new \Core\MetadataFactory();
        $Meta->setTitle('User Profile');
        $Meta->setKeywords('');
        $Meta->setDescription('');
        $Meta->add_assets();
        
        
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $user=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        
        $Forms=new \Bundle\Controllers\Profile\Forms();
        
        if($user['type']==="sole"){
            $form=$Forms->general_info_sole($user);
        }       
        else if($user['type']==="ltd"){
            $form=$Forms->general_info_ltd($user);
        }
        
        $Content->set_form($form, 'profile');
        
        return $this->Kernel;
    }
    
    function login(){
        $this->unlogged();
        
        #create content object
        $Content=new \Core\ContentFactory();
        $Content->init_assets();
        
        #create meta object
        $Meta=new \Core\MetadataFactory();
        $Meta->setTitle('User Profile');
        $Meta->setKeywords('');
        $Meta->setDescription('');
        $Meta->add_assets();
        
        $Request=new \Core\Request();
        
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $userData=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        /* Login */
        $Forms=new \Bundle\Controllers\Index\Forms();
        $loginForm=$Forms->login();
        
        $Content->set_form($loginForm, 'profile');
        
        return $this->Kernel;
    }
    
    function email(){
        $this->unlogged();
        #create content object
        $Content=new \Core\ContentFactory();
        $Content->init_assets();
        
        #create meta object
        $Meta=new \Core\MetadataFactory();
        $Meta->setTitle('User Profile');
        
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $userData=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        $Forms=new \Bundle\Controllers\Profile\Forms();
        $form=$Forms->email($userData);
        
        $Content->set_form($form, 'profile');
        
        return $this->Kernel;
    }
    
    function password(){
        $this->unlogged();
        #create content object
        $Content=new \Core\ContentFactory();
        $Content->init_assets();
        
        #create meta object
        $Meta=new \Core\MetadataFactory();
        $Meta->setTitle('Change Password');
      
        
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $userData=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        $Forms=new \Bundle\Controllers\Profile\Forms();
        $form=$Forms->password();
        
        $Content->set_form($form, 'profile');
        
        return $this->Kernel;
    }
    
    public function ajax_change_email(){
        $this->unlogged(); 
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        
        /* existing details */
        $user=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        /* update details */        
        $DataMngr->update_item_by_id($_SESSION['user']['id'], $this->get_repository()[0], $this->Kernel->Request->post);
        
        /* change in newsletters */        
        $MailChimp=new \Modules\Subscription\MailChimp();        
        $callData=array(
                        'id'                => '5053233c99',
                        'email'             => array('email'=>$user['email']),
                        'new_email'         => array($this->Kernel->Request->post['email']),
                        'double_optin'      => true,
                        'update_existing'   => true,
                        'send_welcome'      => true,
                    );
        $MailChimp->subscribe($callData);
    }
    
    public function ajax_change_password(){
        $this->unlogged();
        $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array('id'=>$_SESSION['user']['id']));
        
        $Login=new Login($this->Kernel->entityManager);        
        $valid=$Login->check_password($entry);
        
        if($valid){
            $entry->setPassword($this->Kernel->Request->post['newPassword']);
            echo "Password was set";
        }
        else {
            echo "Invalid old password";
        }
    }
    
    function ajax_general_change(){
        $this->unlogged();
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        if ($DataMngr->update_item_by_id($_SESSION['user']['id'], $this->get_repository()[0], $this->Kernel->Request->post)){
            echo 'Profile has been updated.';
        }
    }
    
}

?>
