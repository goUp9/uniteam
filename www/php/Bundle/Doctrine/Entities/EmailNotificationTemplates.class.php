<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/EmailNotificationTemplates.php
/**
 * @Entity @Table(name="EmailNotificationTemplates")
 **/

class EmailNotificationTemplates {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $subject;
    
    /** @Column(type="text") */
    protected $body;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    
    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
    
}

?>
