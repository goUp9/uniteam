<?php
namespace Bundle\Controllers\Users;
class Admin extends \Modules\Modules{
    public $repository=["Users","UINQuery"];
    
    private $qualifications=["None","Secondary school","High school school/College","Bachelor's degree","Master's degree","PhD"];
    
    function list_users(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $EdtForm=new EditUserForm($this->Kernel, 'form__edit-user', '');        
        $this->Kernel->Content->set_form($EdtForm->form,"edit");
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function get_users(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        if(\Core\Utils::is_ajax()){
            $DataMngr=new \Modules\DataManager($this->Kernel);
            $usersData=$DataMngr->get_items_per_page($this->get_repository()[0], 10, $this->Kernel->Request->post['page']);
            
            foreach($usersData['data'] as &$item){
                if($item['dateCreated']->getTimestamp()!==FALSE){
                   $item['dateCreated']=  date('Y-m-d H:i',$item['dateCreated']->getTimestamp());
                }
                else {
                   $item['dateCreated']='';
                }
            }
            echo json_encode($usersData);
        }
        return $this->Kernel;
    } 
    
    function search_users(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        if(\Core\Utils::is_ajax()){
            $fields=  $this->Kernel->Request->post['fields'];
            $searchTerm=  $this->Kernel->Request->post['searchVal'];
            

            
            # count all
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('count(u.id)', 'u.id')
            ->from($this->get_repository()[0], 'u');            
            foreach($fields as $key=>$field){
                if($field==="true"){                    
                    $QB->orWhere('u.'.$key." LIKE '%".$searchTerm."%'");
                }
            } 
            $numberOfItems=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

            $pagination=\Core\Utils::pagination($numberOfItems[0][1], 10, $this->Kernel->Request->post['page']);
            
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('u', 'u')
            ->from($this->get_repository()[0], 'u')
            ->leftJoin('u.queries', 'q');
            foreach($fields as $key=>$field){
                if($field==="true"){
                    $QB->orWhere('u.'.$key." LIKE '%".$searchTerm."%'");
                }
            }
            
            $QB->setMaxResults(10)
            ->setFirstResult($pagination['offset'])
            ->orderBy('u.'.  key($fields),'ASC')
            ->groupBy('u.id');
            $usersData= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            
            foreach($usersData as &$item){
                if($item['dateCreated']->getTimestamp()!==FALSE){
                   $item['dateCreated']=  date('Y-m-d H:i',$item['dateCreated']->getTimestamp());
                }
                else {
                   $item['dateCreated']='';
                }
            }
            
            $Response=new \Core\AjaxResult(1, '', array('users'=>$usersData,'pages'=>$pagination['totalPages']));
            echo $Response->to_JSON();
        }
        return $this->Kernel;
    } 
    
    function save_change(){        
        $DataMngr=new \Modules\DataManager($this->Kernel);        
        unset($this->Kernel->Request->post['password']);
        unset($this->Kernel->Request->post['salt']);
        $DataMngr->update_item_by_id($this->Kernel->Request->post['id'], $this->get_repository()[0], $this->Kernel->Request->post);
    }
    
    function filter($type){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        # count all
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('count(u.id)', 'u.id')
        ->from($this->get_repository()[0], 'u')
        ->leftJoin('u.queries', 'q')
        ->where('q.type = :type')
        ->setParameter('type', $type);       
        $numberOfItems=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY); 
        
        $pagination=\Core\Utils::pagination(count($numberOfItems), 10, $this->Kernel->Request->post['page']);
        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('u', 'u')
        ->from($this->get_repository()[0], 'u')
        ->leftJoin('u.queries', 'q')
        ->where('q.type = :type')
        ->setMaxResults(10)
        ->setFirstResult($pagination['offset'])
        ->orderBy('u.id','DESC')
        ->groupBy('u.id')
        ->setParameter('type', $type);  
        $data= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        
        foreach($data as &$item){
            if($item['dateCreated']->getTimestamp()!==FALSE){
               $item['dateCreated']=  date('Y-m-d H:i',$item['dateCreated']->getTimestamp());
            }
            else {
               $item['dateCreated']='';
            }
        }
        
        $Response=new \Core\AjaxResult(1, '',  array('users'=>$data,'pages'=>$pagination['totalPages']));
        echo $Response->to_JSON();
    }
    
    function list_queries($idUser,$type){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $QB = $this->Kernel->entityManager->createQueryBuilder();

        $QB->select('q', 'qw,t,qwhere, places, schedules,whens,finalSupplier,finalAsker')
        ->from($this->get_repository()[1], 'q')
        ->leftJoin('q.whats', 'qw')
        ->leftJoin('qw.tag', 't')
        ->leftJoin('q.wheres', 'qwhere')
        ->leftJoin('qwhere.place', 'places')
        ->leftJoin('q.QueryWhenSchedule', 'schedules')
        ->leftJoin('q.QueryWhenAsker', 'whens')
        ->leftJoin('q.finalSupplier', 'finalSupplier')
        ->leftJoin('q.finalAsker', 'finalAsker')
        ->where('q.idUser='.$idUser)                
        ->andWhere("q.type='".$type."'")
        ->orderBy("q.id","DESC");
        $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        
        foreach($Queries as &$Query){            
            if(isset($Query['QueryWhenSchedule'])&&!empty($Query['QueryWhenSchedule'])){
                foreach($Query['QueryWhenSchedule'] as &$schedule){
                    $schedule['from']=$schedule['fromTime']->format('H:i');
                    $schedule['to']=$schedule['toTime']->format('H:i');
                }
            }
            if(isset($Query['finalSupplier'])&&!empty($Query['finalSupplier'])){
                    $Query['finalSupplier']['qualifName']=  $this->qualifications[$Query['finalSupplier']['qualification']];
            }
        }
//        \Dev\Debug::dump($Queries);
        $this->Kernel->Content->set_data($Queries,"queries");
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/';
        return $this->Kernel;
    }
}
