<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/AdminPayments.php
/**
 * @Entity @Table(name="admin_payments")
 **/

class AdminPayments {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $account;
    
    /** @Column(type="boolean") */
    protected $isLive=FALSE; 
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
    }
    
    public function getIsLive()
    {
        return $this->isLive;
    }

    public function setIsLive($isLive=FALSE)
    {
        $this->isLive= $isLive;
    }
    
}

?>
