<?php
namespace Bundle\Controllers\UserQueries;
class Admin extends \Modules\Modules{
    public $repository=["Users","UINQuery","AdminPayments"];
    
    private $qualifications=["None","Secondary school","High school school/College","Bachelor's degree","Master's degree","PhD"];
    
    function list_queries(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $this->set_payment_settings();
        
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    function get_queries($page){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        $num_per_page=10;

        $this->set_payment_settings();
        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $q =  $QB->select("q","q, whats, tags, wheres, places, whens, finalAsker, schedules, finalSupplier, chosenSupplier, SuppliersFeedback,AskQuery,AskFeedbackUser, chosenAdviser,user")
                ->from($this->get_repository()[1], 'q')
                ->leftJoin("q.whats","whats")
                ->leftJoin("whats.tag","tags")
                ->leftJoin("q.wheres","wheres")
                ->leftJoin('wheres.place', 'places')
                ->leftJoin('q.QueryWhenAsker', 'whens')
                ->leftJoin('q.finalAsker', 'finalAsker')
                ->leftJoin('q.SuppliersFeedback', 'SuppliersFeedback')
                ->leftJoin('SuppliersFeedback.AskQuery', 'AskQuery')
                ->leftJoin('AskQuery.idUser', 'AskFeedbackUser')
                ->leftJoin('q.QueryWhenSchedule', 'schedules')
                ->leftJoin('q.finalSupplier', 'finalSupplier')
                ->leftJoin('q.chosenSupplier', 'chosenSupplier')
                ->leftJoin('q.chosenAdviser', 'chosenAdviser')
                ->leftJoin('q.idUser', 'user')
//                ->setMaxResults($num_per_page)
//                ->setFirstResult($pagination['offset'])
                ->orderBy('q.id','DESC');                      
        $data=$QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $totalItems=  count($data);
        $pagination=[];
        $pagination['data']=array_slice($data, ($page-1)*$num_per_page,$num_per_page);
        $pagination['page']=$page;
        $pagination['totalPages']= round($totalItems/$num_per_page);
//        \Dev\Debug::dump($pagination['data']);
        
        foreach($pagination['data'] as &$item){
            if($item['dateCreated']->getTimestamp()!==FALSE){
                $item['dateCreated']=  date('Y-m-d H:i',$item['dateCreated']->getTimestamp());
            }
            else {
                $item['dateCreated']='';
            }
            if(is_object($item['dateEscrowed'])){
                if($item['dateEscrowed']->getTimestamp()!==FALSE){
                    $item['dateEscrowed']=  date('Y-m-d H:i',$item['dateEscrowed']->getTimestamp());
                }
                else {
                    $item['dateEscrowed']='';
                }
            }
            else {
                    $item['dateEscrowed']='';
            }
            if(isset($item['QueryWhenSchedule'])&&!empty($item['QueryWhenSchedule'])){
                foreach($item['QueryWhenSchedule'] as &$date){                    
                    $timeFrom=date('H:i', $date["fromTime"]->getTimestamp());
                    $timeTo=date('H:i', $date["toTime"]->getTimestamp());
                    $date['from']=$timeFrom;
                    $date['to']=$timeTo;   
                    
                }
            }
            
        }
        
//        \Dev\Debug::dump($pagination['data']);
        
        echo json_encode($pagination);
    }
    
    function search_queries(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $this->set_payment_settings();
        
        if(\Core\Utils::is_ajax()){
            $fields=  $this->Kernel->Request->post['fields'];
            $searchTerm= strtolower($this->Kernel->Request->post['searchVal']);
            $page=$this->Kernel->Request->post['page'];
            $num_per_page=10;

            
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('q', 'q, whats, tags, wheres, places, whens, finalAsker, schedules, finalSupplier, chosenSupplier, chosenAdviser,SuppliersFeedback,AskQuery,AskFeedbackUser,u')
            ->from($this->get_repository()[1], 'q')
            ->leftJoin('q.idUser', 'u')
            ->leftJoin("q.whats","whats")
            ->leftJoin("whats.tag","tags")
            ->leftJoin("q.wheres","wheres")
            ->leftJoin('wheres.place', 'places')
            ->leftJoin('q.QueryWhenAsker', 'whens')
            ->leftJoin('q.finalAsker', 'finalAsker')
            ->leftJoin('q.QueryWhenSchedule', 'schedules')
            ->leftJoin('q.SuppliersFeedback', 'SuppliersFeedback')
            ->leftJoin('SuppliersFeedback.AskQuery', 'AskQuery')
            ->leftJoin('AskQuery.idUser', 'AskFeedbackUser')
            ->leftJoin('q.finalSupplier', 'finalSupplier')
            ->leftJoin('q.chosenSupplier', 'chosenSupplier')
            ->leftJoin('q.chosenAdviser', 'chosenAdviser')
            ->leftJoin('q.idUser', 'user');
            foreach($fields as $key=>$field){
                if($field==="true"){
                    if($key=='id'){
                        $QB->orWhere('q.'.$key."='".$searchTerm."'");
                    }
                    else {
                        if ($this->Kernel->entityManager->getClassMetadata($this->get_repository()[1])->hasField($key)){                        
                            $QB->orWhere('q.'.$key." LIKE '%".$searchTerm."%'");
                        }
                        else if($this->Kernel->entityManager->getClassMetadata($this->get_repository()[0])->hasField($key)){
                            $QB->orWhere('u.'.$key." LIKE '%".$searchTerm."%'");
                        }
                    }
                }
            }
            $QB
//                    ->setMaxResults(10)
//            ->setFirstResult($pagination['offset'])
            ->orderBy('q.id','ASC');
//            ->groupBy('q.id');

            $qData= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            
            $totalItems=  count($qData);
            $pagination=[];
            $pagination['data']=array_slice($qData, ($page-1)*$num_per_page,$num_per_page);
            $pagination['page']=$page;
            $pagination['totalPages']= round($totalItems/$num_per_page);
            foreach($pagination['data'] as &$item){
                # date format
                if($item['dateCreated']->getTimestamp()!==FALSE){
                    $item['dateCreated']=  date('Y-m-d H:i',$item['dateCreated']->getTimestamp());
                }
                else {
                    $item['dateCreated']='';
                }
                if(isset($item['QueryWhenSchedule'])&&!empty($item['QueryWhenSchedule'])){
                    foreach($item['QueryWhenSchedule'] as &$date){                    
                        $timeFrom=date('H:i', $date["fromTime"]->getTimestamp());
                        $timeTo=date('H:i', $date["toTime"]->getTimestamp());
                        $date['from']=$timeFrom;
                        $date['to']=$timeTo;
                    }                    
                }
                
            }
//            \Dev\Debug::dump($qData[0]);
            $Response=new \Core\AjaxResult(1, '', array('queries'=>$pagination['data'],'pages'=>$pagination['totalPages']));
            echo $Response->to_JSON();
        }
        return $this->Kernel;
    } 
    
    private function set_payment_settings() {
        $Settings = $this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById(1);
        $paymentSettings['account']=$Settings->getAccount();
        if($Settings->getIsLive()){
            $paymentSettings['isLive']=TRUE;
        }
        $this->Kernel->Content->set_data($paymentSettings,'paymentSettings');
    }
}
