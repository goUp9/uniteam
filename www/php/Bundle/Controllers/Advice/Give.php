<?php
namespace Bundle\Controllers\Advice;
class Give extends \Modules\Modules{
    
    public $repository=array("UINQuery","Tags", "Users");
    
    function main(){
        if(isset($this->Kernel->Request->post['idQuery'])){
            $query_id=  $this->Kernel->Request->post['idQuery'];
            $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($query_id);
            
            $AdviserQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($this->Kernel->Request->post['adviser']);
            if(is_object($AdviserQuery)){
                $Advice=new \Bundle\Doctrine\Entities\Advices();
                $Advice->setBudget($this->Kernel->Request->post['budget']);
                $Advice->setMsg($this->Kernel->Request->post['advice']);

                $Advice->setAdvicedBy($AdviserQuery);
                $this->Kernel->entityManager->persist($Advice);
                $this->Kernel->entityManager->flush();
                $UINQuery->setAdvices($Advice);

                #new notifications
                if(is_object($UINQuery)){
                    $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $UINQuery->getUser(), $UINQuery, \Modules\UserNotifications\Notification::TYPE_NEW_ADVICE);                       
                    $Notification->Entity->setAdvices($Advice);
                    $Notification->Entity->setAdviceRequestQuery($AdviserQuery);
                    $Notification->send();
                    #email
                    $replacementArray['queryData']['id']=$UINQuery->getId();
                    $replacementArray['username']=$UINQuery->getUser()->getUsername();
                    $ENotifications=new \Modules\UserNotifications\Email($UINQuery->getUser(), 'There is a new Advice for your Query #'.$UINQuery->getId().' on uinteam', 'new_advice.html.twig', $replacementArray);
                    $ENotifications->send();

                    $this->Kernel->entityManager->persist($UINQuery);
                    $this->Kernel->entityManager->flush(); 
                }
            }
        }
        $link=$this->Kernel->Content->insert_asset('link','notifications');       
        header('location:'.LINKS_PRE.$link['href']);
        return $this->Kernel;
    }
    
    private function get_asker_query($idQuery){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('q', 'qw,t, qwhere, places,whens,finalAsker')
        ->from($this->get_repository()[0], 'q')
        ->leftJoin('q.whats', 'qw')                    
        ->leftJoin('qw.tag', 't')
        ->leftJoin('q.wheres', 'qwhere')
        ->leftJoin('qwhere.place', 'places')
        ->leftJoin('q.QueryWhenAsker', 'whens')
        ->leftJoin('q.finalAsker', 'finalAsker')
        ->where('q.id='.$idQuery)                
        ->andWhere("q.type='ask'")
        ->andWhere("t.status=1");
        $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);                
        return $Queries;        
    }
    
}
