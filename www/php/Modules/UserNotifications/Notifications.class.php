<?php
namespace Modules\UserNotifications;
class Notifications extends \Modules\Users\Profile{
    use \Core\Mapping;
    public $repository="UserNotifications";
    
    public function main(){
        $this->unlogged();
        
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        return $this->Kernel;
    }
    
    public function get_by_user($sortBy){        
        if(isset($_SESSION['user']['id'])){
            if(strlen($sortBy)===0){
                $sortBy='n.dateCreated';
            }
            #change order for particular cases
            if($sortBy==='n.isRead'){
                $order='ASC';
            }
            else {
                $order='DESC';
            }
            
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $q =  $QB->select("n","n,query, whats, tag")
                ->from($this->get_repository()[0], 'n') 
                ->leftJoin('n.idQuery', 'query')
                ->leftJoin('query.whats', 'whats')
                ->leftJoin('whats.tag', 'tag')
                ->where('n.isArchived!='.'1')
                ->andWhere('n.idRecipient='.$_SESSION['user']['id'])
                ->addOrderBy($sortBy,$order)
                ->addOrderBy('n.dateCreated','DESC')
                ->addOrderBy('query.id','DESC')
                ;  
            
            $Notifications=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            foreach($Notifications as &$N){
                $N['type']=  Notification::$types[$N['type']];
                $N['created']= $N['dateCreated']->format('H:i Y-m-d');
            }
            
            if(!empty($Notifications)){
                $AjxRes=new \Core\AjaxResult(1, '', $Notifications);
            }
            else {
                $AjxRes=new \Core\AjaxResult(0, 'no notifications');
            }
            echo $AjxRes->to_JSON();
        }
    }
    
    public function get_by_user_archived($sortBy){        
        if(isset($_SESSION['user']['id'])){
            if(strlen($sortBy)===0){
                $sortBy='n.dateCreated';
            }
            #change order for particular cases
            if($sortBy==='n.isRead'){
                $order='ASC';
            }
            else {
                $order='DESC';
            }
            
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $q =  $QB->select("n","n,query, whats, tag")
                ->from($this->get_repository()[0], 'n') 
                ->leftJoin('n.idQuery', 'query')
                ->leftJoin('query.whats', 'whats')
                ->leftJoin('whats.tag', 'tag')
                ->where('n.isArchived='.'1')
                ->andWhere('n.idRecipient='.$_SESSION['user']['id'])
                ->addOrderBy($sortBy,$order)
                ->addOrderBy('n.dateCreated','DESC')
                ->addOrderBy('query.id','DESC')
                ;  
            
            $Notifications=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            foreach($Notifications as &$N){
                $N['type']=  Notification::$types[$N['type']];
                $N['created']= $N['dateCreated']->format('H:i Y-m-d');
            }
            
            if(!empty($Notifications)){
                $AjxRes=new \Core\AjaxResult(1, '', $Notifications);
            }
            else {
                $AjxRes=new \Core\AjaxResult(0, 'no notifications');
            }
            echo $AjxRes->to_JSON();
        }
    }
    
    function unarchive_notification($id){
        if(\Core\Utils::is_ajax()){
            $Notification=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
            $Notification->setIsArchived(FALSE);
            $this->Kernel->entityManager->persist($Notification);
            $this->Kernel->entityManager->flush();
        }
    }
    
    function archive_notification($id){
        if(\Core\Utils::is_ajax()){
            $Notification=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
            $Notification->setIsArchived(TRUE);
            $this->Kernel->entityManager->persist($Notification);
            $this->Kernel->entityManager->flush();
        }
    }
    
}
