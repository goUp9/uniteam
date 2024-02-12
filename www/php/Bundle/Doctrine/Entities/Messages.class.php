<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Messages.php
/**
 * @Entity @Table(name="Messages")
 **/

class Messages {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="text") */
    protected $msg;
    
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Messages")
     *  @JoinColumn(name="idSender", referencedColumnName="id") 
    **/
    public $idSender;
    
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
    
    public function getIdSender()
    {
        return $this->idSender;
    }

    public function setIdSender(Users $User)
    {
         $this->idSender = $User;
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
