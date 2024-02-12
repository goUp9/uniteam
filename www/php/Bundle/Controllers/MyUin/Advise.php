<?php
namespace Bundle\Controllers\MyUin;
class Advise extends \Modules\Users\Profile{
    use \Core\Mapping;
    
    public $repository=array("UINQuery");
            
    function main(){
        $this->unlogged();
        
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        $VideosCtrl=new Videos($this->Kernel);
        $videos=$VideosCtrl->get_videos();
        $this->Kernel->Content->set_data($videos,'videos');
        
        return $this->Kernel;
    }
    
    function ajax_get_advices(){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qw,t, qwhere, places, schedules')
            ->from($this->get_repository()[0], 'q')
            ->leftJoin('q.whats', 'qw')
            ->leftJoin('qw.tag', 't')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenSchedule', 'schedules')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='advice'")
            ->andWhere("t.status=1")
            ->andWhere("q.isArchived=0 OR q.isArchived IS NULL")
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
    
    function ajax_get_archived(){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q')
            ->from($this->get_repository()[0], 'q')            
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='advice'")
            ->andWhere("q.isArchived=1")
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                     
            
            echo json_encode($Queries);
        }
    }
    
    function ajax_get_details($id){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qw,t, qwhere, places,whens')
            ->from($this->get_repository()[0], 'q')
            ->leftJoin('q.whats', 'qw')                    
            ->leftJoin('qw.tag', 't')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenSchedule', 'whens')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='advice'")
            ->andWhere("t.status=1")
            ->andWhere("q.isArchived=1")
            ->andWhere("q.id=".$id)
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }
        foreach($Queries as &$Query){ 
                foreach($Query['QueryWhenSchedule'] as &$schedule){
                    $schedule['from']=$schedule['fromTime']->format('H:i');
                    $schedule['to']=$schedule['toTime']->format('H:i');
                }
        }
        echo json_encode($Queries[0]);
    }
    
    function unarchive_query($id){
        if(\Core\Utils::is_ajax()){
            $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
            $Query->unarchive();
            $this->Kernel->entityManager->persist($Query);
            $this->Kernel->entityManager->flush();
        }
    }
    
    function archive_query($id){
        if(\Core\Utils::is_ajax()){
            $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
            $Query->archive();
            $this->Kernel->entityManager->persist($Query);
            $this->Kernel->entityManager->flush();
        }
    }
    
}
