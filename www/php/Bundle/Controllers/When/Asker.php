<?php
namespace Bundle\Controllers\When;
class Asker extends \Modules\Modules{
    public $repository=['QueryWhenASker','UINQuery'];
            
    function main(){
        
        return $this->Kernel;
    }
    
    function confirm(){
        if(isset($this->Kernel->Request->post['query_id'])&&!empty($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            $query_id=$_SESSION['currentQuery'];
        }
        
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);

        $UINQuery->removeWhens();
        
        $i=1;
        
        foreach($this->Kernel->Request->post['dates'] as $item){
            $dateArr=explode(' ',$item);
            $month[$i]=  date('m', strtotime($dateArr[1]));
            
            $day[$i]=$dateArr[2];
            $year[$i]=$dateArr[3];
            
            $timeArr=  explode(':', $dateArr[4]);
            $hours[$i]=$timeArr[0];
            $minutes[$i]=$timeArr[1];            
            
            $i++;
        }
        
        
        $When=new \Bundle\Doctrine\Entities\QueryWhenAsker();
        $When->setDate1($hours[1], $minutes[1], $year[1], $month[1], $day[1], $UINQuery->getTimezone());
        if(count($this->Kernel->Request->post['dates'])===3){
            $When->setDate2($hours[2], $minutes[2], $year[2], $month[2], $day[2], $UINQuery->getTimezone());
            $When->setExpDate($hours[3], $minutes[3], $year[3], $month[3], $day[3], $UINQuery->getTimezone());
        }
        else {
            $When->setExpDate($hours[2], $minutes[2], $year[2], $month[2], $day[2], $UINQuery->getTimezone());
        }

        $When->setDateType($this->Kernel->Request->post['type']);
        
        $this->Kernel->entityManager->persist($When);
        $this->Kernel->entityManager->flush();
        
        $UINQuery->setWhens($When);
        
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();
        
        return $this->Kernel;
    }
    
    function get_when(){        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'whens')
            ->from($this->get_repository()[1], 'q')           
            ->leftJoin('q.QueryWhenAsker', 'whens')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.id=".$this->Kernel->Request->post['query_id'])
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);            
            echo json_encode($Queries);
    }
    
    function get_timezone(){
        if(isset($this->Kernel->Request->post['query_id'])){
            $query_id=$this->Kernel->Request->post['query_id'];
        }
        else {
            if(isset($_SESSION['currentQuery'])){
                $query_id=$_SESSION['currentQuery'];
            }
        }
        if(isset($query_id)){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qwhere, places')
            ->from($this->get_repository()[1], 'q')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->where('q.id='.$query_id)
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            if(isset($Queries[0]['wheres'][0]['place']['lat'])&& !empty($Queries[0]['wheres'][0]['place']['lat'])){
                $url='https://maps.googleapis.com/maps/api/timezone/json?location='.$Queries[0]['wheres'][0]['place']['lat'].','.$Queries[0]['wheres'][0]['place']['lng'].'&timestamp='.(time()/1000).'&key='.'AIzaSyCywyEwLdA5afiBExoMHKJTiRGYOvg2oG0';

                $result=file_get_contents($url);
                $response=json_decode($result,TRUE);
                $this->set_timezone($query_id, $response["timeZoneId"]);
                echo $response["timeZoneId"];
            }
            else {
                $this->set_timezone($query_id, "Europe/London");
                echo "Europe/London";                
            }
        }
        else {
            $this->set_timezone($query_id, "Europe/London");
            echo "Europe/London";
        }

    }
    
    function set_timezone($query_id, $timezone){
        $UINQuery=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneById($query_id);
        if(is_object($UINQuery)){
            $UINQuery->setTimezone($timezone);
        }
        $this->Kernel->entityManager->persist($UINQuery);
        $this->Kernel->entityManager->flush();
    }

}
