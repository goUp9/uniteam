<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/FinalSupplier.php
/**
 * @Entity @Table(name="FinalSupplier")
 **/

class FinalSupplier {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="integer") */
    protected $experience;
    
    /** @Column(type="integer") */
    protected $qualification;
    
    /**
     * @OneToOne(targetEntity="UINQuery", inversedBy="FinalSupplier")
     */
    private $UINQuery;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getExperience()
    {
        return $this->experience;
    }

    public function setExperience($experience)
    {
        $this->experience = $experience;
    }
    
    public function getQualification()
    {
        return $this->qualification;
    }

    public function setQualification($qualification)
    {
        $this->qualification = $qualification;
    }
    
    public function setUINQuery(UINQuery $UINQuery){
        $UINQuery->set_finalSupplier($this);
    }
    
}

?>
