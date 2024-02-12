<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/QueryWhenAsker.php
/**
 * @Entity @Table(name="QueryWhenAsker")
 **/

class QueryWhenAsker {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="datetime") */
    protected $date1;
    
    /** @Column(type="datetime", nullable=true) */
    protected $date2;
    
    /** @Column(type="string") */
    protected $dateType;
    
    /** @Column(type="datetime") */
    protected $expDate;
    

    
    /**  
     *  @ManyToMany(targetEntity="UINQuery", mappedBy="QueryWhenAsker", cascade={"persist", "remove"})  
     **/
    private $idQuery;
    
    public function __construct() {
        $this->idQuery = new \Doctrine\Common\Collections\ArrayCollection();
    }
      
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getDate1(){
        return $this->date1;
    }
    
    public function setDate1($hours, $minutes,$year, $month, $day, $timezone="Europe/London"){ 
        $DT = new \DateTime('now',new \DateTimeZone($timezone));
        $DT->setTime($hours, $minutes);
        $DT->setDate($year, $month, $day); 
        $DT->setTimezone(new \DateTimeZone("Europe/London"));                
        $this->date1=$DT;
        
    }    
    
    public function getDate2(){
        return $this->date2;
    }
    
    public function setDate2($hours, $minutes,$year, $month, $day, $timezone="Europe/London"){        
        $DT = new \DateTime('now',new \DateTimeZone($timezone));
        $DT->setTime($hours, $minutes);
        $DT->setDate($year, $month, $day);
        $DT->setTimezone(new \DateTimeZone("Europe/London")); 
        $this->date2=$DT;
    }
    
    public function addUinQuery(UINQuery $idQuery){
        $this->idQuery[] = $idQuery;
    }
    
    public function getExpDate(){
        return $this->expDate;
    }
    
    public function setExpDate($hours, $minutes,$year, $month, $day, $timezone="Europe/London"){
        $DT = new \DateTime('now',new \DateTimeZone($timezone));
        $DT->setTime($hours, $minutes);
        $DT->setDate($year, $month, $day);
        $DT->setTimezone(new \DateTimeZone("Europe/London")); 
        $this->expDate=$DT;
    }
    
    public function getDateType(){
        return $this->expType;
    }
    
    public function setDateType($dateType){
        $this->dateType=$dateType;
    }
    
    public function getIdQuery(){
        return $this->idQuery;
    }
    
    public function setIdQuery(UINQuery $UINQuery=NULL){
        $this->idQuery[] = $UINQuery;
        return $this;
    }
    
}

?>
