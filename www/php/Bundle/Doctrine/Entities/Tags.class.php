<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Tags.php
/**
 * @Entity @Table(name="Tags")
 **/

class Tags {
    
    /** 
     *  @Id @Column(type="string", unique=true)   
     */
    protected $tag;
    
    /**  
     *  @ManyToOne(targetEntity="TagGroups", inversedBy="Tags", cascade={"persist", "remove"})
     *  @JoinColumn(name="tagGroup", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $tagGroup;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":1}) */
    protected $status;
    
    /*
     * @OneToMany(targetEntity="QueryWhat", mappedBy="tag", cascade={"persist", "remove"})
     */
    protected $querieswhat;
          
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Tags")
     *  @JoinColumn(name="idUser", referencedColumnName="id", nullable=TRUE) 
    **/
    private $idUser;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }
    
    public function getTagGroup()
    {
        return $this->tagGroup;
    }

    public function setTagGroup(TagGroups $tagGroup)
    {
         $this->tagGroup = $tagGroup;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function setIdUser(Users $idUser){
        $this->idUser=$idUser;
    }
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }
    
}

?>
