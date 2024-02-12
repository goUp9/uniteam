<?php
namespace Modules\Gallery;
class ImageLoader{
//    public $gallery_path="data/gallery/";
//    public $gallery_thumb_folder="thumbs/";
//    public $gallery_big_folder="big/";
//    public $gallery_medium_folder="medium/";
//    public $gallery_small_folder="small/";
//    public $gallery_fullsize_folder="fullsize/";
//    public $gallery_custom_folder="";
    
    public $db_pic_col='pic';
    
    #sizes
    public $size_big=array('width'=>1000,'height'=>1000,'canvas'=>1000);
    public $size_medium=array('width'=>600,'height'=>600,'canvas'=>600);
    public $size_small=array('width'=>150,'height'=>150,'canvas'=>150);
    public $size_thumb=array('width'=>50,'height'=>50,'canvas'=>50);
    public $size_custom=array('width'=>'','height'=>'','canvas'=>'');


    # size flags
    const SIZE_FULL=1;
    const SIZE_BIG=2;
    const SIZE_MEDIUM=4;
    const SIZE_THUMB=8;
    const SIZE_SMALL=16;
    const SIZE_CUSTOM=32;
    const SIZE_ALL=-1;
    
    public function uplaod($callback=NULL, $args=NULL, $sizeFLAGS=NULL){
        $PicMngr=new \Modules\PicturesManager\GeneralUpload();
        $PicMngr->set_inputName($this->db_pic_col);
        $Content=new \Core\ContentFactory();        
        $PicMngr->set_uploadPath($Content->insert_asset('path', 'gallery_fullsize')['href']);
        return $PicMngr->upload(false, array($this,'save_gallery_images'),array($callback, $args,  $sizeFLAGS));
    }   
    
    public function save_gallery_images($imgData,$callback=NULL, $args=NULL,$sizeFLAG=NULL){        
        $img = \WideImage::load(dirname(__FILE__).'../../../../'.$imgData['path'].$imgData['name'].'.'.$imgData['ext']);
        $Content=new \Core\ContentFactory();
        if($sizeFLAG!=NULL){
            if($sizeFLAG & self::SIZE_THUMB){                
                $img->resize($this->size_thumb['canvas'])
                    ->resizeCanvas($this->size_thumb['width'],  $this->size_thumb['height'],'center',0)
                    ->autoCrop(0,0,1,0)
                    ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_thumbs')['href'].$imgData['name'].'.jpg');
            }
             if($sizeFLAG & self::SIZE_SMALL){
                $img->resize($this->size_small['canvas'])
                    ->resizeCanvas($this->size_small['width'],  $this->size_small['height'],'center',0)
                    ->autoCrop(0,0,1,0)
                    ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_small')['href'].$imgData['name'].'.jpg');
            }
            if($sizeFLAG & self::SIZE_BIG){
                $img->resize($this->size_big['canvas'])
                    ->resizeCanvas($this->size_big['width'],  $this->size_big['height'],'center',0)
                    ->autoCrop(0,0,1,0)
                    ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_big')['href'].$imgData['name'].'.jpg');
            }
            if($sizeFLAG & self::SIZE_MEDIUM){
                $img->resize($this->size_medium['canvas'])
                    ->resizeCanvas($this->size_medium['width'],  $this->size_medium['height'],'center',0)
                    ->autoCrop(0,0,1,0)
                    ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_medium')['href'].$imgData['name'].'.jpg');
            }
             if($sizeFLAG & self::SIZE_CUSTOM){
                $img->resize($this->size_custom['canvas'])
                    ->resizeCanvas($this->size_custom['width'],  $this->size_custom['height'],'center',0)
                    ->autoCrop(0,0,1,0)
                    ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_custom')['href'].$imgData['name'].'.jpg');
            }
            if(!($sizeFLAG & self::SIZE_FULL) || !($sizeFLAG & self::SIZE_ALL)){                
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_fullsize')['href'].$imgData['name'].'.jpg');
            }
            
            /* callback */
            if($args!=NULL){
                array_unshift($args, $imgData);
            }
            else {                
                $args[0]=$imgData;
            }
            if(!empty($callback)){                
                if(is_callable($callback)){                    
                    $imgData=call_user_func_array($callback, $args);
                }
            }
            
            return $imgData;
        }
    }
    
}

?>
