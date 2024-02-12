<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Notifications.php
/**
 * @Entity @Table(name="Notifications")
 **/

class Notifications {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="text") */
    protected $msg;
    
    /** @Column(type="boolean") */
    protected $isImportant;
    
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Messages")
     *  @JoinColumn(name="idRecipient", referencedColumnName="id") 
    **/
    protected $idRecipient;
    
    /** @Column(name="is_read",type="boolean", nullable=TRUE, options={"default":0}) */
    protected $read;




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
    
    public function getIsImportant()
    {
        return $this->isImportant;
    }

    public function setIsImportant($is_important=FALSE)
    {
        $this->isImportant = $is_important;
    }
    
    public function getIdRecipient()
    {
        return $this->idRecipient;
    }

    public function setIdRecipient(Users $User)
    {
         $this->idRecipient = $User;
    }
    
    public function getRead()
    {
        return $this->read;
    }

    public function setRead($read)
    {
        $this->read = $read;
    }
    
    
    
}

?>
