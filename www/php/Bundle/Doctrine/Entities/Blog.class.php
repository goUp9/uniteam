<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Blog.php
/**
 * @Entity @Table(name="blog") @HasLifecycleCallbacks
 **/

class Blog {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $title;
    
    /** @Column(type="text") */
    protected $text;
    
    /** @Column(type="text") */
    protected $keywordsString;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /**  
     *  @OneToMany(targetEntity="Blog_Comments", mappedBy="Blog", cascade={"persist", "remove"}) 
    **/
    public $Blog_Comments;
    
    
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
    
    public function getKeywordsString(){
        return $this->keywordsString;
    }
    
    public function setKeywordsString($keywordsString){
        $this->keywordsString=$keywordsString;
    }
    
    public function getBlogComments(){
        return $this->Blog_Comments;
    }
    
    public function setBlog_Comments(Blog_Comments $BlogComments){
        $this->Blog_Comments=$BlogComments;
    }
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }
    
    /** @PrePersist */
    public function preCreate(){
        $this->setDateCreated();
    }
    
}

?>
