<?php
namespace Bundle\Controllers\MyUin;
class Supply extends \Modules\Users\Profile{
    use \Core\Mapping;
    
    public $repository=array("UINQuery");
    
    private $qualifications=["None","Secondary school","High school school/College","Bachelor's degree","Master's degree","PhD"];
            
    function main(){
        $this->unlogged();
        
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        $VideosCtrl=new Videos($this->Kernel);
        $videos=$VideosCtrl->get_videos();
        $this->Kernel->Content->set_data($videos,'videos');
        
        return $this->Kernel;
    }
    
    function ajax_get_supplies(){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qw,t, qwhere, places, schedules,finalSupplier')
            ->from($this->get_repository()[0], 'q')
            ->leftJoin('q.whats', 'qw')
            ->leftJoin('qw.tag', 't')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenSchedule', 'schedules')
            ->leftJoin('q.finalSupplier', 'finalSupplier')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='supply'")
            ->andWhere("t.status=1")
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            foreach($Queries as &$Query){ 
                foreach($Query['QueryWhenSchedule'] as &$schedule){
                    $schedule['from']=$schedule['fromTime']->format('H:i');
                    $schedule['to']=$schedule['toTime']->format('H:i');
                }
                if(isset($Query['finalSupplier']['qualification'])){
                    $Query['finalSupplier']['qualifName']=  $this->qualifications[$Query['finalSupplier']['qualification']];
                }
            }
            echo json_encode($Queries);
        }
    }
    
}
