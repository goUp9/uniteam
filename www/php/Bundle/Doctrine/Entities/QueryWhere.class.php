<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/QueryWhere.php
/**
 * @Entity @Table(name="QueryWhere")
 **/

class QueryWhere {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="float")**/
    protected $radius;
    
    /**  
     *  @ManyToOne(targetEntity="UINQuery", inversedBy="QueryWhere", cascade={"persist"})
     *  @JoinColumn(name="idQuery", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $idQuery;


  /**  
     *  @ManyToOne(targetEntity="Places", inversedBy="QueryWhere", cascade={"persist"})
     *  @JoinColumn(name="placeId", referencedColumnName="placeId", nullable=TRUE) 
    **/
    public $place;     
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getRadius(){
        return $this->radius;
    }
    
    public function setRadius($radius){
        $this->radius=$radius;
    }
    
    public function getPlace(){        
        return $this->place;
    }
    
    public function setPlace(Places $place = NULL){        
        $this->place = $place;
        return $this;
    }
    
    public function setIdQuery(UINQuery $idQuery = NULL){        
        $this->idQuery = $idQuery;
        return $this;
    }
    
}

?>
