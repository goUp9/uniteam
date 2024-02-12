<?php
namespace Bundle\Controllers\Matching;
class Process extends \Modules\Modules{
    
    public $repository=array("UINQuery","Tags", "Users");
    
    function main($idQuery=NULL,$forward){
            if($idQuery==0){
                $idQuery=$_SESSION['currentQuery'];
            }
            $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);
            $UINQuery->setIsIncomplete(FALSE);
            $UINQuery->setIsPending(TRUE);
            if($forward=='true'){ 
                if(isset($this->Kernel->Request->post['adviser'])){
                    $UINQuery->get_FinalAsker()->setBudget($this->Kernel->Request->post['budget']);
                    $UINQuery->get_FinalAsker()->setAdvice($this->Kernel->Request->post['advice']);                    

                    $Adviser = $this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($this->Kernel->Request->post['adviser']);
                    $UINQuery->set_chosenAdviser($Adviser);
                    if(!$UINQuery->getCAdvisers()->contains($Adviser)){
                        $UINQuery->setCAdvisers($Adviser);
                    }
                }
            }
            $this->Kernel->entityManager->persist($UINQuery);
            $this->Kernel->entityManager->flush();

            $this->match_what($idQuery);
            if($forward=='true'){                
                $link=$this->Kernel->Content->insert_asset('link','myuin__asking');       
                header('location:'.LINKS_PRE.$link['href']);
            }
    }
    
    function advisers_main($idQuery=NULL){
            if($idQuery==0){
                $idQuery=$_SESSION['currentQuery'];
            }
            $this->match_what_adviser($idQuery);
    }
    
    private function match_what($idQuery){
        $Asker=$this->get_asker_query($idQuery);
        
        $Suppliers=  $this->get_suppliers($Asker);

        $Suppliers=$this->filter_where($Asker, $Suppliers);
        
        $matchingS=$this->filter_when($Asker, $Suppliers);
        
        $matchingS=  $this->remove_noRegUser_queries($matchingS);
        
        
        
        
        
        #set status
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery); 
        
        $this->send_notifications_to_matches($matchingS, $UINQuery);
        
        if(is_object($UINQuery)){             
            if(!empty($matchingS)){
                $status='pending: '.count($matchingS).' Suppliers matching';
                #<REMOVE AFTER TESTING>
                $usernames=': ';
                foreach($matchingS as $s){
                    $usernames.=$s['idUser']['username'].',';
                }
                $status.=$usernames;
                #</REMOVE AFTER TESTING>
                $UINQuery->setStatus($status);
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush($UINQuery);
                
                $this->create_cron_job($Asker[0]['QueryWhenAsker'][0]['expDate'], $idQuery);
            }
            else {
                $UINQuery->setStatus('No suppliers found');
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush($UINQuery);
            }
        }
    }
    
    private function remove_noRegUser_queries($SuppliersQueries){
        foreach($SuppliersQueries as $key=>&$S){
            if($S['idUser']['username']===NULL){
                unset($SuppliersQueries[$key]);
            }
        }
        return $SuppliersQueries;
    }
    
    private function match_what_adviser($idQuery){
        $Asker=$this->get_asker_query($idQuery);
        $Advisers=  $this->get_advisers($Asker);
        
        $Advisers=$this->filter_where($Asker, $Advisers);
        
        $matchingS=$this->filter_when($Asker, $Advisers);
        
        $matchingS=  $this->remove_noRegUser_queries($matchingS);
        
        #set status
        $UINQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($idQuery);  
        
        if(is_object($UINQuery)){             
            if(!empty($matchingS)){
                $status='pending: '.count($matchingS).' Advisers matching';
                #<REMOVE AFTER TESTING>
                $usernames=': ';
                foreach($matchingS as $s){
                    $usernames.=$s['idUser']['username'].',';
                }
                $status.=$usernames;
                #</REMOVE AFTER TESTING>
                $UINQuery->setStatus($status);
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush($UINQuery);
                
                $this->send_notifications_to_advisers($matchingS, $UINQuery);
                
            }
            else {
                $UINQuery->setStatus('No adviser found');
                $this->Kernel->entityManager->persist($UINQuery);
                $this->Kernel->entityManager->flush($UINQuery);
            }
        }
//        \Dev\Debug::dump($matchingS);
    }
    
    private function create_cron_job($expDate, $idQuery){
        // CRON
        $CrontabMngr=new \CrontabManager\CrontabManager();
        $Job=$CrontabMngr->newJob();
        
//        $DateLondon=new \DateTime('now', new \DateTimeZone('Europe/London'));
//        $timezone_offset=  abs(timezone_offset_get(new \DateTimeZone('America/Denver'), $DateLondon));
        $timezone_offset=  abs(\Core\Utils::get_timezone_difference());
        
        $minutes=date( "i", $expDate->getTimestamp()-$timezone_offset); // 7 hours differnce with Utah time (bluehost)
        $hours=date( "H", $expDate->getTimestamp()-$timezone_offset);
        $days=date( "d", $expDate->getTimestamp()-$timezone_offset);
        $month=date( "m", $expDate->getTimestamp()-$timezone_offset);

        $Job->on($minutes.' '.$hours.' '.$days.' '.$month.' *');

        $Job->doJob('wget -q -O temp.txt  http://'.$_SERVER['HTTP_HOST'].'/ajax/select-suppliers/'.$idQuery.'/');

        
        $CrontabMngr->add($Job);
        
        $CrontabMngr->save();
        $CrontabMngr->cleanManager();
//        \Dev\Debug::dump($CrontabMngr->jobExists('select-suppliers\/'.$idQuery));
//        $CrontabMngr->deleteJob('select-suppliers\/'.$idQuery);
//        $CrontabMngr->save(false);
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
    
    private function get_suppliers($AskerQuery){        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('q', 'u,qw,t, qwhere, places, schedules')
        ->from($this->get_repository()[0], 'q')
        ->leftJoin('q.idUser','u')
        ->leftJoin('q.whats', 'qw')                    
        ->leftJoin('qw.tag', 't')
        ->leftJoin('q.wheres', 'qwhere')
        ->leftJoin('qwhere.place', 'places')
        ->leftJoin('q.QueryWhenSchedule', 'schedules');        
        foreach($AskerQuery[0]['whats'] as $tag){
            $QB->orWhere("t.tag='".$tag['tag']['tag']."'");
        }   
        $QB->andWhere("q.type='supply'")
        ->andWhere("t.status=1");
        $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        return $Queries;
    }
    
    private function get_advisers($AskerQuery){        
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('q', 'u,qw,t, qwhere, places, schedules')
        ->from($this->get_repository()[0], 'q')
        ->leftJoin('q.idUser','u')
        ->leftJoin('q.whats', 'qw')                    
        ->leftJoin('qw.tag', 't')
        ->leftJoin('q.wheres', 'qwhere')
        ->leftJoin('qwhere.place', 'places')
        ->leftJoin('q.QueryWhenSchedule', 'schedules');        
        foreach($AskerQuery[0]['whats'] as $tag){
            $QB->orWhere("t.tag='".$tag['tag']['tag']."'");
        }   
        $QB->andWhere("q.type='advice'")
        ->andWhere("t.status=1");
        $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        return $Queries;
    }
    
    private function filter_where($Asker, $Suppliers){
        $matchingS=[];
        if(isset($Asker[0]['wheres'])&&isset($Asker[0]['wheres'][0]['place'])){
            $lat1=$Asker[0]['wheres'][0]['place']['lat'];
            $lng1=$Asker[0]['wheres'][0]['place']['lng'];        
            foreach($Suppliers as $Supplier){
                if(isset($Supplier['wheres'])&&!empty($Supplier['wheres'])){
                    foreach($Supplier['wheres'] as $place){
                        $lat2=$place['place']['lat'];
                        $lng2=$place['place']['lng'];
                        $miles=$this->get_distance($lat1, $lng1, $lat2, $lng2);                
                        if($miles<=$place['radius']){
                            array_push($matchingS, $Supplier);
                        }
                    }
                }
                else {
                    array_push($matchingS, $Supplier);
                }
            }
        }
        else {
            $matchingS=$Suppliers;
        }
        return $matchingS;
    }
    
    private function get_distance($lat1, $lng1, $lat2, $lng2){
        
        $distance=(3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lng2-$lng1)*($lng2-$lng1))/180);
        $distance=number_format($distance, 2, '.', '');
        
        
        return $distance;
    }
   
    
    private function filter_when($Asker, $Suppliers){ 
        $matchinS=[];        
        switch($Asker[0]['QueryWhenAsker'][0]['dateType']){
            case 'on this day':
                //check date vs day of Week
                $weekDay=date( "l", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());                
                foreach($Suppliers as $Supplier){
                    foreach($Supplier['QueryWhenSchedule'] as $schedule){                       
                        if($schedule['weekDay']===$weekDay){
                            // check time
                            $hoursAsker=date( "H", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());                            
                            if(isset($schedule['fromTime'])&&isset($schedule['toTime'])){                                
                                $hoursFromSupplier=date( "H", $schedule['fromTime']->getTimestamp());
                                $hoursToSupplier=date( "H", $schedule['toTime']->getTimestamp());
                                if($hoursToSupplier=='00'){
                                    $hoursToSupplier='24';
                                }
                                if($hoursAsker>$hoursFromSupplier && $hoursAsker<$hoursToSupplier){
                                    array_push($matchinS, $Supplier);
                                }
                                else if($hoursAsker===$hoursFromSupplier){
                                    $minutesAsker=date( "i", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                    $minutesFromSupplier=date( "i", $schedule['fromTime']->getTimestamp());
                                    if($minutesAsker>$minutesFromSupplier){
                                        array_push($matchinS, $Supplier);
                                    }
                                }
                                else if($hoursAsker===$hoursToSupplier){
                                    $minutesAsker=date( "i", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                    $minutesToSupplier=date( "i", $schedule['toTime']->getTimestamp());
                                    if($minutesAsker<$minutesToSupplier){
                                        array_push($matchinS, $Supplier);
                                    }
                                }
                            }
                        }
                    }
                }
                break;
            case 'before':
                    $today=date('Ymd');                    
                    //today
                    if($today==  date( "Ymd", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp())){ 
                        $weekDay=date( "l", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                        foreach($Suppliers as $Supplier){
                            foreach($Supplier['QueryWhenSchedule'] as $schedule){
                                if($schedule['weekDay']===$weekDay){
                                    // check time
                                    $hoursAskerFrom=date( "H", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());                                     
                                    if(isset($schedule['fromTime'])&&isset($schedule['toTime'])){                                
                                        $hoursFromSupplier=date( "H", $schedule['fromTime']->getTimestamp());
                                        $hoursToSupplier=date( "H", $schedule['toTime']->getTimestamp());  
                                        if($hoursToSupplier=='00'){
                                            $hoursToSupplier='24';
                                        }
                                        if($hoursAsker>$hoursFromSupplier && $hoursAsker<$hoursToSupplier){
                                            array_push($matchinS, $Supplier);
                                        }
                                        else if($hoursAsker===$hoursFromSupplier){
                                            $minutesAsker=date( "i", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                            $minutesFromSupplier=date( "i", $schedule['fromTime']->getTimestamp());
                                            if($minutesAsker>$minutesFromSupplier){
                                                array_push($matchinS, $Supplier);
                                            }
                                        }
                                        else if($hoursAsker===$hoursToSupplier){
                                            $minutesAsker=date( "i", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                            $minutesToSupplier=date( "i", $schedule['toTime']->getTimestamp());
                                            if($minutesAsker<$minutesToSupplier){
                                                array_push($matchinS, $Supplier);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // if less than a week                     
                    else if((date( "Ymd", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp())-$today)<8){                        
                        $begin =new \DateTime();
                        $end = $Asker[0]['QueryWhenAsker'][0]['date1']; 

                        $interval = new \DateInterval('P1D');
                        $daterange = new \DatePeriod($begin, $interval ,$end);
                        
//                        \Dev\Debug::dump($daterange);
                        foreach($Suppliers as $Supplier){
                            $flag=false;
                            foreach($daterange as $date){ 
                                foreach($Supplier['QueryWhenSchedule'] as $schedule){
                                    if($schedule['weekDay']===$date->format("l")){
                                        array_push($matchinS, $Supplier);
                                        $flag=true;
                                        break;
                                    }
                                }                                
                                if($flag){
                                    break;
                                }
                            }
                            if($flag){
                                break;
                            }
                        }
                    }
                    // if more than a week
                    else {
                        array_push($matchinS, $Supplier);
                    }
                break;
            case 'range':
                    //if today
                    $today=date('Ymd');
                    if($today==  date( "Ymd", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp())&&$today==  date( "Ymd", $Asker[0]['QueryWhenAsker'][0]['date2']->getTimestamp())){ 
                        $weekDay=date( "l", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                        foreach($Suppliers as $Supplier){
                            foreach($Supplier['QueryWhenSchedule'] as $schedule){
                                if($schedule['weekDay']===$weekDay){
                                    // check time
                                    $hoursAskerFrom=date( "H", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                    $hoursAskerTo=date( "H", $Asker[0]['QueryWhenAsker'][0]['date2']->getTimestamp());
                                    if($hoursAskerTo=='00'){
                                        $hoursAskerTo='24';
                                    }
                                    if(isset($schedule['fromTime'])&&isset($schedule['toTime'])){                                
                                        $hoursFromSupplier=date( "H", $schedule['fromTime']->getTimestamp());
                                        $hoursToSupplier=date( "H", $schedule['toTime']->getTimestamp()); 
                                        if($hoursToSupplier=='00'){
                                            $hoursToSupplier='24';
                                        }
                                        if($hoursAskerFrom>$hoursFromSupplier  || $hoursAskerTo<$hoursToSupplier){
                                            array_push($matchinS, $Supplier);
                                        }
                                        else if($hoursAskerFrom===$hoursFromSupplier){
                                            $minutesAskerFrom=date( "i", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp());
                                            $minutesFromSupplier=date( "i", $schedule['fromTime']->getTimestamp());
                                            if($minutesAskerFrom>$minutesFromSupplier){
                                                array_push($matchinS, $Supplier);
                                            }
                                        }
                                        else if($hoursAskerTo===$hoursToSupplier){
                                            $minutesAskerTo=date( "i", $Asker[0]['QueryWhenAsker'][0]['date2']->getTimestamp());
                                            $minutesToSupplier=date( "i", $schedule['toTime']->getTimestamp());
                                            if($minutesAskerTo<$minutesToSupplier){
                                                array_push($matchinS, $Supplier);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // if less than a week
                    else if((date( "Ymd", $Asker[0]['QueryWhenAsker'][0]['date1']->getTimestamp())-$today)<8){                        
                        $begin =$Asker[0]['QueryWhenAsker'][0]['date1'];
                        $end = $Asker[0]['QueryWhenAsker'][0]['date2']; 

                        $interval = new \DateInterval('P1D');
                        $daterange = new \DatePeriod($begin, $interval ,$end);

                        foreach($Suppliers as $Supplier){
                            $flag=false;
                            foreach($daterange as $date){ 
                                foreach($Supplier['QueryWhenSchedule'] as $schedule){
                                    if($schedule['weekDay']===$date->format("l")){
                                        array_push($matchinS, $Supplier);
                                        $flag=true;
                                        break;
                                    }
                                }                                
                                if($flag){
                                    break;
                                }
                            }
                            if($flag){
                                break;
                            }
                        }
                    }
                    // if more than a week
                    else {
                        array_push($matchinS, $Supplier);
                    }
                break;
        }  
        
        return $matchinS;
    }

    
    private function send_notifications_to_matches($matches, $UINQuery){        
        foreach($matches as $match){            
            $User = $this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($match['idUser']['id']);            
            #new notifications
            $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $User, $UINQuery, \Modules\UserNotifications\Notification::TYPE_NEW_ASKER_REQUEST);
            $Notification->send();
            #email
            $replacementArray['queryData']['id']=$UINQuery->getId();
            $ENotifications=new \Modules\UserNotifications\Email($User, 'There is a new Asker request for you on UINteam.', 'new_asker_request.html.twig', $replacementArray);
            $ENotifications->send();  
        }
    }
    
    private function send_notifications_to_advisers($matches, $UINQuery){        
        foreach($matches as $match){
            $User = $this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneById($match['idUser']['id']);
            #new notifications
            $Notification=\Modules\UserNotifications\Notification::new_notification($this->Kernel, $User, $UINQuery, \Modules\UserNotifications\Notification::TYPE_NEW_ADVICE_REQUEST);                       
            $RequestQuery = $this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($match['id']);
            $Notification->Entity->setAdviceRequestQuery($RequestQuery);
            $Notification->send();
            #email
            $replacementArray['username']=$User->getUsername();
            $ENotifications=new \Modules\UserNotifications\Email($User, 'There is a new Advice request for you on UINteam.', 'confirmation_for_asker.html.twig', $replacementArray);
            $ENotifications->send();  
        }
    }
    
}
