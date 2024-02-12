<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/TagGroups.php
/**
 * @Entity @Table(name="TagGroups")
 **/

class TagGroups {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $name;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
}

?>
