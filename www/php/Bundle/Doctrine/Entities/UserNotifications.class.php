<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/UserNotifications.php
/**
 * @Entity @Table(name="user_notifications") @HasLifecycleCallbacks
 **/

class UserNotifications {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="integer") */
    protected $type;
        
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="UserNotifications")
     *  @JoinColumn(name="idRecipient", referencedColumnName="id") 
    **/
    protected $idRecipient;
    
    /**  
     *  @ManyToOne(targetEntity="UINQuery", inversedBy="UserNotifications")
     *  @JoinColumn(name="idQuery", referencedColumnName="id") 
    **/
    protected $idQuery;
    
    /** @Column(name="is_read",type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isRead;

    /** @Column(name="is_archived",type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isArchived;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /**
     * @ManyToOne(targetEntity="Advices", inversedBy="UserNotifications")
     */
    private $Advices;
    
    /**
     * @ManyToOne(targetEntity="UINQuery", inversedBy="UserNotifications")
     */
    private $AdviceRequestQuery;


    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getIdRecipient()
    {
        return $this->idRecipient;
    }

    public function setIdRecipient(Users $User)
    {
         $this->idRecipient = $User;
    }
    
    public function getIdQuery()
    {
        return $this->idQuery;
    }

    public function setIdQuery(UINQuery $idQuery)
    {
         $this->idQuery = $idQuery;
    }
    
    public function getIsRead()
    {
        return $this->isRead;
    }

    public function setIsRead($isRead=FALSE)
    {
        $this->isRead = $isRead;
    }
    
    public function getIsArchived()
    {
        return $this->isArchived;
    }

    public function setIsArchived($isArchived=FALSE)
    {
        $this->isArchived= $isArchived;
    }
    
    public function getAdvices(){
        return $this->Advices;
    }
    
    public function setAdvices(Advices $Advices)
    {
        $this->Advices= $Advices;
    }
    
    public function getAdviceRequestQuery(){
        return $this->AdviceRequestQuery;
    }
    
    public function setAdviceRequestQuery(UINQuery $AdviceRequestQuery)
    {
        $this->AdviceRequestQuery= $AdviceRequestQuery;
    }
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        if($this->getIdQuery()->getTimezone()){
            $timezone=$this->getIdQuery()->getTimezone();
        }
        else {
            $timezone='Europe/London';
        }
        $this->dateCreated = new \DateTime("now", new \DateTimeZone($timezone));
    }
    
    
    /** @PrePersist */
    public function preCreate(){
        $this->setDateCreated();
        $this->setIsRead();
        $this->setIsArchived();
    }
    
}

?>
