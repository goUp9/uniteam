<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/QueryWhat.php
/**
 * @Entity @Table(name="QueryWhat")
 **/

class QueryWhat {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /**  
     *  @ManyToOne(targetEntity="UINQuery", inversedBy="QueryWhat", cascade={"persist"})
     *  @JoinColumn(name="idQuery", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $idQuery;


  /**  
     *  @ManyToOne(targetEntity="Tags", inversedBy="QueryWhat", cascade={"persist"})
     *  @JoinColumn(name="tag", referencedColumnName="tag", nullable=TRUE) 
    **/
    public $tag;     
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTag(){        
        return $this->tag;
    }
    
    public function setTag(Tags $tag = NULL){        
        $this->tag = $tag;
        return $this;
    }
    
    public function setIdQuery(UINQuery $idQuery = NULL){        
        $this->idQuery = $idQuery;
        return $this;
    }
    
}

?>
