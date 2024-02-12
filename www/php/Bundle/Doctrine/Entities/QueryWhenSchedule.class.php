<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/QueryWhenSchedule.php
/**
 * @Entity @Table(name="QueryWhenSchedule")
 **/

class QueryWhenSchedule {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="datetime") */
    protected $fromTime;
    
    /** @Column(type="datetime") */
    protected $toTime;
    
    /** @Column(type="string") */
    protected $weekDay;
    
    /**  
     *  @ManyToMany(targetEntity="UINQuery", mappedBy="QueryWhenSchedule", cascade={"persist", "remove"})  
     **/
    private $idQuery;
    
    public function __construct() {
        $this->idQuery = new \Doctrine\Common\Collections\ArrayCollection();
    }
      
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFromTime(){
        return $this->fromTime;
    }
    
    public function setFromTime($hours,$minutes){
        $DT = new \DateTime();
        $DT->setTime($hours, $minutes);
        $this->fromTime=$DT;
    }
    
    public function getToTime(){
        return $this->toTime;
    }
    
    public function setToTime($hours, $minutes){
        $DT = new \DateTime();
        $DT->setTime($hours, $minutes);
        $this->toTime=$DT;
    }
    
    public function getWeekDay(){
        return $this->weekDay;
    }
    
    public function setWeekDay($weekday){
        $this->weekDay=$weekday;
    }
    
    public function addUinQuery(UINQuery $idQuery){
        $this->idQuery[] = $idQuery;
    }
    
}

?>
