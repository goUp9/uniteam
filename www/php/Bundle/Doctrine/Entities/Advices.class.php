<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Advices.php
/**
 * @Entity @Table(name="advices")
 **/

class Advices {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="text") */
    protected $msg;
    
    /** @Column(type="float") */
    protected $budget;
    
    /**
     * @ManyToOne(targetEntity="UINQuery", inversedBy="Advices")
     */
    private $UINQuery;
    
    /**
     * @ManyToOne(targetEntity="UINQuery", inversedBy="Advices")
     */
    private $AdvicedBy;
    
    /**
     * @OneToMany(targetEntity="UserNotifications", mappedBy="Advices")
     */
    private $UserNotifications;
    
    public function __construct() {
        $this->UserNotifications = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
    }
    
    public function getBudget()
    {
        return $this->budget;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    }
    
    public function getUINQuery(){
        return $this->UINQuery;
    }
    
    public function setUINQuery(UINQuery $UINQuery){
        $this->UINQuery=$UINQuery;
        $UINQuery->setAdvices($this);
    }
    
    public function getAdvicedBy(){
        return $this->UINQuery;
    }
    
    public function setAdvicedBy(UINQuery $AdvicedBy){
        $this->AdvicedBy=$AdvicedBy;
        $AdvicedBy->setAdvicedBy($this);
    }
    
    public function getUserNotifications(){
        return $this->UserNotifications;
    }
    
    public function setUserNotifications(UserNotifications $UserNotifications){
        $this->UserNotifications[]=$UserNotifications;
        $UserNotifications->setAdvices($this);
    }
    
}

?>
