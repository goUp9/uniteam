<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/TextManager.php
/**
 * @Entity @Table(name="TextManager")
 **/

class TextManager {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $title;
    
    /** @Column(type="text") */
    protected $text;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
    
}

?>
