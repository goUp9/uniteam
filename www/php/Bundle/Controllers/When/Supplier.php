<?php
namespace Bundle\Controllers\When;
class Supplier extends \Modules\Modules{
    public $repository=['QuerySchedule','UINQuery'];
            
    function main(){
        
        return $this->Kernel;
    }
    
    function confirm(){
        if(isset($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            $query_id=$this->Kernel->Session->access->currentQuery;
        }
        
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);
        
        $UINQuery->removeSchedules();
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();
        
        foreach($this->Kernel->Request->post['schedule'] as $day){
            $QuerySchedule=  new \Bundle\Doctrine\Entities\QueryWhenSchedule();
            $QuerySchedule->setFromTime($day['from']['hours'], $day['from']['minutes']);
            $QuerySchedule->setToTime($day['to']['hours'], $day['to']['minutes']);
            $QuerySchedule->setWeekDay($day['weekday']);
            $this->Kernel->entityManager->persist($QuerySchedule);
            $this->Kernel->entityManager->flush();
            
            $UINQuery->setSchedules($QuerySchedule);
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();
        }
        
        
        return $this->Kernel;
    }
    
    function get_schedule(){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'schedules')
            ->from($this->get_repository()[1], 'q')           
            ->leftJoin('q.QueryWhenSchedule', 'schedules')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.id=".$this->Kernel->Request->post['query_id'])
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            foreach($Queries as &$Query){ 
                foreach($Query['QueryWhenSchedule'] as &$schedule){
                    $schedule['from']=$schedule['fromTime']->format('H:i');
                    $schedule['to']=$schedule['toTime']->format('H:i');
                }
            }
            echo json_encode($Queries);
    }

}
