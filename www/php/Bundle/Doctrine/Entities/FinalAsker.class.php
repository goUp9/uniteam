<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/FinalSupplier.php
/**
 * @Entity @Table(name="FinalAsker")
 **/

class FinalAsker {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="float", nullable=TRUE) */
    protected $budget;
    
    /** @Column(type="text", nullable=TRUE) */
    protected $msg;
    
    /** @Column(type="text", nullable=TRUE) */
    protected $currency;
    
    /** @Column(type="text", nullable=TRUE) */
    protected $adviceMsg;
    
    /** @Column(type="text", nullable=TRUE) */
    protected $advice;
    
    /** @Column(type="boolean", nullable=TRUE) */
    protected $isAdviseOnBudgetNeeded;
    
    /**
     * @OneToOne(targetEntity="UINQuery", inversedBy="FinalAsker")
     */
    private $UINQuery;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getBudget()
    {
        return $this->budget;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    }
    
    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function getAdviceMsg()
    {
        return $this->adviceMsg;
    }

    public function setAdviceMsg($msg)
    {
        $this->adviceMsg = $msg;
    }
    
    public function getAdvice()
    {
        return $this->advice;
    }

    public function setAdvice($advice)
    {
        $this->advice = $advice;
    }
    
    public function getIsAdviseOnBudgetNeeded(){
        return $this->isAdviseOnBudgetNeeded;
    }
    
    public function setIsAdviseOnBudgetNeeded($needed=FALSE){
        $this->isAdviseOnBudgetNeeded=$needed;
    }
    
    public function getUINQuery(){
        return $this->UINQuery;
    }
    
    public function setUINQuery(UINQuery $UINQuery){
        $UINQuery->set_finalAsker($this);
    }
    
}

?>
