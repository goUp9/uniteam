<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Stories.php
/**
 * @Entity @Table(name="stories") @HasLifecycleCallbacks
 **/

class Stories {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string") */
    protected $title;
    
    /** @Column(type="string",nullable=true) */
    protected $cover=NULL;
    
    /** @Column(type="text") */
    protected $text;
    
    /** @Column(type="text") */
    protected $keywordsString;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
        
    
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
    
    public function getCover()
    {
        return $this->cover;
    }

    public function setCover()
    {
        $Img=\WideImage::loadFromUpload('cover');
        $ImgResized=$Img->resize(150, 150, 'outside');
        $ImgCropped=$ImgResized->crop(0, 0, 150, 150);
        $name=md5(rand(0, 99)).'.jpg';
        $ImgCropped->saveToFile($_SERVER['DOCUMENT_ROOT'].'/deployment/data/stories_covers/'.$name);
        $this->cover = $name;
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
