<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/SuppliersFeedback.php
/**
 * @Entity @Table(name="SuppliersFeedback")
 **/

class SuppliersFeedback {
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;  
    
    /** @Column(type="integer") */
    protected $feedback;
    
    /**  
     *  @ManyToMany(targetEntity="UINQuery", mappedBy="SuppliersFeedback")
     *  @JoinTable(name="uinqueries_suppliers_feedback",joinColumns={@JoinColumn(name="supplier", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)})  
     **/
    private $SuppliersFeedback;
    
    
    /**
     * @OneToOne(targetEntity="UINQuery", inversedBy="SuppliersFeedback")
     * @JoinColumn(onDelete="SET NULL")
     */
    private $AskQuery;
    
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id=$id;
    }

    public function getFeedback(){
        return $this->feedback;
    }

    public function setFeedback($feedback){
        $this->feedback = $feedback;
    }
    
    public function setAskQuery(UINQuery $AskQuery){
        $this->AskQuery=$AskQuery;
    }
    
    public function getAskQuery(){
        return $this->AskQuery;
    }
    
    
}

?>
