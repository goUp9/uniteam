<?php
namespace Modules\Users;
class Users extends \Modules\Modules{
    public $repository="Users";
    
    public function get_current_user(){
        $entry = $this->Kernel->entityManager
                    ->getRepository($this->get_repository()[0])
                    ->findOneBy(array("id"=>  $_SESSION['user']['id']));
        return $entry;
    }
}
