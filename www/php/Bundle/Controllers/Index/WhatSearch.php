<?php
namespace Bundle\Controllers\Index;
class WhatSearch extends \Modules\Modules{
    public $repository=array("Tags","Users", "UINQuery","QueryWhat");
    
    
    function ajax_get_tags(){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('t', 't.tag')
        ->from($this->get_repository()[0], 't')
        ->where('t.tag LIKE :search')
        ->andWhere('t.status=1')
        ->setMaxResults(5)
        ->setParameter('search', $this->Kernel->Request->get['search_query'].'%');
        $Tags= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        
        $tagsStrict=array();
        foreach ($Tags as $t){
            array_push($tagsStrict, array('tag'=>$t['tag']));
        }
        
        if(count($tagsStrict)<5){
            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('t', 't.tag')
            ->from($this->get_repository()[0], 't')
            ->where('t.tag LIKE :search')
            ->andWhere('t.status=1');
            foreach ($tagsStrict as $tagStrict){
                $QB->andWhere("t.tag!='".$tagStrict['tag']."'");
            }
            $QB->setMaxResults(5-count($tagsStrict))
            ->setParameter('search', '%'.$this->Kernel->Request->get['search_query'].'%');
            $Tags= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

            $tags=array();
            foreach ($Tags as $t){
                array_push($tags, array('tag'=>$t['tag']));
            }
            $tags=  array_merge($tagsStrict,$tags);            
        }
        else {
            $tags=$tagsStrict;
        }

        echo json_encode(array('results'=>$tags));
    }
    
    function ajax_confirm(){
        if(isset($this->Kernel->Session->access->whatSearch)){
            $Login=new \Bundle\Controllers\Users\Login($this->Kernel);
            if($Login->is_logged()){
                $User=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneBy(array('id'=>$this->Kernel->Session->access->user['id'])); 
            
                if(is_object($User)){
                    if($User->getFeedbackBlocked()){
                        echo 'blocked';
                        die();
                    }
                }
            } 
            
                if($this->Kernel->Request->get['type']!=='supply'){ // for asker and adviser create a new query
                    $Query=new \Bundle\Doctrine\Entities\UINQuery();
                    $Query->setType($this->Kernel->Request->get['type']);
                    if (isset($User)&&is_object($User)){
                        $Query->setIdUser($User); 
                    }

                    foreach($this->Kernel->Session->access->whatSearch as $tag){
                        if($tag!==''){
                            $Tag=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array('tag'=>$tag));
                            if($Tag===NULL){
                                $Tag=new \Bundle\Doctrine\Entities\Tags();
                                $Tag->setTag($tag);
                                $Tag->setStatus(TRUE);
                                $this->Kernel->entityManager->persist($Tag);
                            }
                            $QueryWhat=new \Bundle\Doctrine\Entities\QueryWhat();                                            
                            $QueryWhat->setTag($Tag);
                            $QueryWhat->setIdQuery($Query);                            
                            $this->Kernel->entityManager->persist($QueryWhat);
                            $this->Kernel->entityManager->flush();
                        }
                    }                
                    $Query->setStatus('incomplete');
                    $this->Kernel->entityManager->persist($Query);
                    $this->Kernel->entityManager->flush();
                }
                else { // for supplier
                    if(isset($User)&&is_object($User)){
                        $QB = $this->Kernel->entityManager->createQueryBuilder();
                        $QB->select('q', 'q')
                        ->from($this->get_repository()[2], 'q')
                        ->where('q.idUser='.$_SESSION['user']['id'])                    
                        ->andWhere("q.type='supply'")
                        ->setMaxResults(1);
                        $Query= $QB->getQuery()->getResult();
                    }
                    
                    if(!isset($Query)||empty($Query)){
                        $Query=new \Bundle\Doctrine\Entities\UINQuery();
                    }
                    else {
                        $Query=$Query[0];
                    }
                    $Query->setType($this->Kernel->Request->get['type']);
                    if(isset($User)&&is_object($User)){
                        $Query->setIdUser($User);
                    }
                    
                    $this->Kernel->entityManager->persist($Query);
                    $this->Kernel->entityManager->flush();                    

                    foreach($this->Kernel->Session->access->whatSearch as $tag){
                        if($tag!==''){
                            $Tag=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneBy(array('tag'=>$tag));                            
                            if($Tag===NULL){
                                $Tag=new \Bundle\Doctrine\Entities\Tags();
                                $Tag->setTag($tag);
                                $Tag->setStatus(TRUE);
                                $this->Kernel->entityManager->persist($Tag);
                            }                            
                            $QB = $this->Kernel->entityManager->createQueryBuilder();
                                $QB->select('qw', 'qw')
                                ->from($this->get_repository()[3], 'qw')
                                ->where("qw.tag='".$tag."'")                    
                                ->andWhere("qw.idQuery=".$Query->getId())
                                ->setMaxResults(1);
                                $TagExists= $QB->getQuery()->getResult();
                                if(empty($TagExists)){
                                    $QueryWhat=new \Bundle\Doctrine\Entities\QueryWhat();                                            
                                    $QueryWhat->setTag($Tag);
                                    $QueryWhat->setIdQuery($Query);
                                    $this->Kernel->entityManager->persist($QueryWhat);
                                    $this->Kernel->entityManager->flush();
                                }
                        }
                    }                    
                }
        } 
        
        $this->Kernel->Session->set($Query->getId(),'currentQuery');       
    }
    
    function ajax_add_tags_to_session(){   
        $tags=explode(',',$this->Kernel->Request->post['tags']);        
        $_SESSION['whatSearch']=$tags;        
    }
    
    function ajax_add_tag(){        
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $User=$this->Kernel->entityManager->getRepository($this->get_repository()[1])->findOneBy(array('id'=>$this->Kernel->Session->access->user['id'])); 
        if(is_object($User)){
            $data=array(
                "tag"=>  $this->Kernel->Request->post['tag'],
                "status"=> true,
                "idUser"=> $User,
                "dateCreated"=>''
            );
            $DataMngr->create_new_item($this->get_repository()[0], $data);
            $PHPMailer=new \PHPMailer();
            $PHPMailer->addAddress("info@uinteam.com");
            $PHPMailer->Body='<p>A new tag: "'.$this->Kernel->Request->post['tag'].'" has been added</p>';
            echo $PHPMailer->send();
        }
    }
}
