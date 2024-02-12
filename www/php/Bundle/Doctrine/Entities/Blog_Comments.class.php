<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Blog_Comments.php
/**
 * @Entity @Table(name="blog_comments") @HasLifecycleCallbacks
 **/

class Blog_Comments {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="text") */
    protected $text;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="Blog_Comments")
     *  @JoinColumn(name="idUser", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $idUser;
    
    /**  
     *  @ManyToOne(targetEntity="Blog", inversedBy="Blog_Comments")
     *  @JoinColumn(name="idArticle", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $Blog;
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    } 
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }
    
    public function getUser(){
        return $this->idUser;
    }
    
    public function setIdUser(Users $idUser){
        $this->idUser=$idUser;
    }
    
    public function getBlog(){
        return $this->Blog;
    }
    
    public function setBlog(Blog $Blog){
        $this->Blog=$Blog;
    }
    
    /** @PrePersist */
    public function preCreate(){
        $this->setDateCreated();
    }
}

?>
