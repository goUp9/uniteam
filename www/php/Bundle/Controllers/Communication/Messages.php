<?php
namespace Bundle\Controllers\Communication;
class Messages extends \Modules\Users\Profile{
    use \Core\Mapping;
    public $repository="Messages";
    
    function main(){
        $this->unlogged();
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        $VideosCtrl=new \Bundle\Controllers\MyUin\Videos($this->Kernel);
        $videos=$VideosCtrl->get_videos();
        $this->Kernel->Content->set_data($videos,'videos');
        
        return $this->Kernel;
    }
    
    function send_msg(){
        $this->unlogged();
        $MsgForm=new MessageForm($this->Kernel, 'form__msg', '');
        $this->Kernel->Content->set_form($MsgForm->form,'msg');
        
        return $this->Kernel;
    }
    
    function api_count_unread(){
        if(isset($this->Kernel->Session->access->user)&& is_object($this->Kernel->Session->access->user)){
            $q = $this->Kernel->entityManager->createQuery("select COUNT(n.id) from ".$this->get_repository()[0]." n WHERE n.idRecipient= ".$this->Kernel->Session->access->user->id." AND (n.read=0 OR n.read IS NULL)");
            return $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);            
        }
        return 0;
    }
    
    function action_send_pm(){
        if(\Core\Utils::is_ajax()){
            $Users=new \Bundle\Controllers\Users\Users($this->Kernel);             
            $Sender = $this->Kernel->entityManager->getRepository($Users->get_repository()[0])->findOneBy(array('id'=>$this->Kernel->Session->access->user['id']));
            $Recipient = $this->Kernel->entityManager->getRepository($Users->get_repository()[0])->findOneBy(array('username'=>$this->Kernel->Request->post['recipient']));
            
            $Message=new \Bundle\Doctrine\Entities\Messages();
            $Message->setMsg($this->Kernel->Request->post['msg']);
            $Message->setIdRecipient($Recipient);
            $Message->setIdSender($Sender);
            
            $link=$this->Kernel->Content->insert_asset("link","myuin__msgs");
            $replacementArray=array(
                "link"=>LINKS_PRE.$link['href']
            );
            new \Modules\EmailNotifications\EmailNotification($this->Kernel, new \PHPMailer, $Recipient->getEmail(), 2, $replacementArray,TRUE);
            
            $this->Kernel->entityManager->persist($Message);
            $this->Kernel->entityManager->flush();
        }
    }
    
    function get_messages_for_user(){
        if(\Core\Utils::is_ajax()){
            $q = $this->Kernel->entityManager->createQuery("select COUNT(m.id) from ".$this->get_repository()[0]." m WHERE m.idRecipient= ".$this->Kernel->Session->access->user['id']);        
            $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
            
            $num_per_page=25;
            $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page,$this->Kernel->Request->post['page']);

            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('m.id, m.msg, m.read', 'u.username')
            ->from($this->get_repository()[0], 'm')
            ->leftJoin('m.idSender', 'u')
            ->where('m.idRecipient = :recipient')
            ->setMaxResults($num_per_page)
            ->setFirstResult($pagination['offset'])
            ->setParameter('recipient', $this->Kernel->Session->access->user['id'])
            ->orderBy('m.id','DESC');
            $Messages= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            
            foreach($Messages as &$msg){
                $text= str_split(strip_tags($msg['msg']),30);
                $msg['preview']=$text[0];
            }
            
            $pagination['data']=$Messages;            
            
            echo json_encode($pagination);
        }
    }
    
    function get_messages_from_user(){
        if(\Core\Utils::is_ajax()){
            $q = $this->Kernel->entityManager->createQuery("select COUNT(m.id) from ".$this->get_repository()[0]." m WHERE m.idRecipient= ".$this->Kernel->Session->access->user['id']);        
            $numberOfItems=$q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);  
            
            $num_per_page=25;
            $pagination=\Core\Utils::pagination($numberOfItems, $num_per_page,$this->Kernel->Request->post['page']);

            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('m.id, m.msg, m.read', 'u.username')
            ->from($this->get_repository()[0], 'm')
            ->leftJoin('m.idSender', 'u')
            ->where('m.idSender = :sender')
            ->setMaxResults($num_per_page)
            ->setFirstResult($pagination['offset'])
            ->setParameter('sender', $this->Kernel->Session->access->user['id'])
            ->orderBy('m.id','DESC');
            $Messages= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            
            foreach($Messages as &$msg){
                $text= str_split(strip_tags($msg['msg']),30);
                $msg['preview']=$text[0];
            }
            
            $pagination['data']=$Messages;            
            
            echo json_encode($pagination);
        }
    }
    
    function update_message_data(){
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $DataMngr->update_item_by_id($this->Kernel->Request->post['id'], $this->get_repository()[0], $this->Kernel->Request->post);
    }
    
}
