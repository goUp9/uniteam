<?php
namespace Bundle\Controllers\MyUin;
class Ask extends \Modules\Users\Profile{
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
    
    function archive(){
        $this->unlogged();
        
        return $this->Kernel;
    }
    
    function ajax_get_asks(){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qw,t, qwhere, places,whens,finalAsker')
            ->from($this->get_repository()[0], 'q')
            ->leftJoin('q.whats', 'qw')                    
            ->leftJoin('qw.tag', 't')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenAsker', 'whens')
            ->leftJoin('q.finalAsker', 'finalAsker')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='ask'")
            ->andWhere("t.status=1")
            ->andWhere("q.isArchived=0 OR q.isArchived IS NULL")
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);            
            
            foreach ($Queries as &$Q){                
                if($Q['timezone']){
                    $timezone=$Q['timezone'];
                }
                else {
                    $timezone="Europe/London";
                }
                if(isset($Q['QueryWhenAsker'][0])){
                    $Q['QueryWhenAsker'][0]['date1']=$Q['QueryWhenAsker'][0]['date1']->setTimezone(new \DateTimeZone($timezone))->format('H:i d/m/Y');
                }
                if(isset($Q['QueryWhenAsker'][0])&&isset($Q['QueryWhenAsker'][0]['date2'])){
                    $Q['QueryWhenAsker'][0]['date2']=$Q['QueryWhenAsker'][0]['date2']->setTimezone(new \DateTimeZone($timezone))->format('H:i d/m/Y');
                }
                if(isset($Q['QueryWhenAsker'][0])){
                    $Q['QueryWhenAsker'][0]["expDate"]=$Q['QueryWhenAsker'][0]["expDate"]->setTimezone(new \DateTimeZone($timezone))->format('H:i d/m/Y');
                }
                
                if(isset($Q['whats'])&&!empty($Q['whats'])&&isset($Q['QueryWhenAsker'])&&!empty($Q['QueryWhenAsker'])&&isset($Q['finalAsker'])&&!empty($Q['finalAsker'])){
                    $Q['complete']=true;
                }
                else {
                    $Q['complete']=false;
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
            ->andWhere("q.type='ask'")
            ->andWhere("q.isArchived=1")
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                     
            
            echo json_encode($Queries);
        }
    }
    
    function ajax_get_details($id){
        if(\Core\Utils::is_ajax()){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'qw,t, qwhere, places,whens,finalAsker')
            ->from($this->get_repository()[0], 'q')
            ->leftJoin('q.whats', 'qw')                    
            ->leftJoin('qw.tag', 't')
            ->leftJoin('q.wheres', 'qwhere')
            ->leftJoin('qwhere.place', 'places')
            ->leftJoin('q.QueryWhenAsker', 'whens')
            ->leftJoin('q.finalAsker', 'finalAsker')
            ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
            ->andWhere("q.type='ask'")
            ->andWhere("t.status=1")
            ->andWhere("q.isArchived=1")
            ->andWhere("q.id=".$id)
            ->orderBy("q.id","DESC");
            $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }
        foreach ($Queries as &$Q){
                
                if(isset($Q['QueryWhenAsker'][0])){
                    $Q['QueryWhenAsker'][0]['date1']=date('H:i d/m/Y',$Q['QueryWhenAsker'][0]['date1']->getTimestamp());
                }
                if(isset($Q['QueryWhenAsker'][0])&&isset($Q['QueryWhenAsker'][0]['date2'])){
                    $Q['QueryWhenAsker'][0]['date2']=date('H:i d/m/Y',$Q['QueryWhenAsker'][0]['date2']->getTimestamp());
                }
                if(isset($Q['QueryWhenAsker'][0])){
                    $Q['QueryWhenAsker'][0]["expDate"]=date('H:i d/m/Y',$Q['QueryWhenAsker'][0]["expDate"]->getTimestamp());
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
