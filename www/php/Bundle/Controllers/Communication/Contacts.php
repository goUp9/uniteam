<?php
namespace Bundle\Controllers\Communication;
class Contacts extends \Modules\Modules{
    public $repository=array("Contacts","Users");
    
    function ajax_get_contacts(){      

            $QB = $this->Kernel->entityManager->createQueryBuilder();
            $QB->select('c', 'u.id, u.username')
            ->from($this->get_repository()[0], 'c')
            ->leftJoin('c.idContact', 'u')
            ->where('u.username LIKE :search')
            ->andWhere('c.idUser = :currentUser')
            ->setParameter('search', '%'.$this->Kernel->Request->get['search_query'].'%')
            ->setParameter('currentUser', $this->Kernel->Session->access->user['id']);
            $Contacts= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);    

            $usernames=array();
            foreach ($Contacts as $Contact){
                $username=$Contact['username'];
                array_push($usernames, array('username'=>$username));
            }
            echo json_encode(array('results'=>$usernames));
        }
    
}
