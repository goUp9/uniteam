<?php
namespace Bundle\Controllers\Communication;
class Notifications extends \Modules\Users\Profile{
    use \Core\Mapping;
    public $repository="UserNotifications";

    
    function api_count_unread(){
        if(isset($this->Kernel->Session->access->user)&& is_object($this->Kernel->Session->access->user)){
            $q = $this->Kernel->entityManager->createQuery("select COUNT(n.id) from ".$this->get_repository()[0]." n WHERE n.idRecipient= ".$this->Kernel->Session->access->user->id." AND (n.isRead=0 OR n.isRead IS NULL) AND n.isArchived=0");
            return $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);
        }
        return 0;
    }    
    
    
    
}
