<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/MyUinVideos.php
/**
 * @Entity @Table(name="my_uin_videos")
 **/

class MyUinVideos {
    
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
        
    /** @Column(type="string", nullable=TRUE) */
    protected $title;
    
    /** @Column(type="text", nullable=TRUE) */
    protected $video;
       

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
    
    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        if(strpos($video, 'watch?')!==FALSE){
            $id=  explode('=', $video)[1];
            $video='https://www.youtube.com/embed/'.$id;
        }
        $this->video = $video;
    }
    
    
}

?>
