<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Contacts.php
/**
 * @Entity @Table(name="Contacts")
 **/

class Contacts {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Contacts")
     *  @JoinColumn(name="idUser", referencedColumnName="id") 
    **/
    protected $idUser;
    
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Contacts")
     *  @JoinColumn(name="idContact", referencedColumnName="id") 
    **/
    protected $idContact;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser(Users $User)
    {
         $this->idUser = $User;
    }
    
    public function getIdContact()
    {
        return $this->idContact;
    }

    public function setIdContact(Users $User)
    {
         $this->idContact = $User;
    }
    
}

?>
