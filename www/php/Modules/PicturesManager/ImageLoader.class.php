<?php
namespace Modules\PicturesManager;
class ImageLoader extends Definitions { 
    
    public function __construct() {
    }
    
    #sizes
    public $size_big=array('width'=>1000,'height'=>1000,'canvas'=>1000);
    public $size_medium=array('width'=>600,'height'=>600,'canvas'=>600);
    public $size_small=array('width'=>150,'height'=>150,'canvas'=>150);
    public $size_thumb=array('width'=>75,'height'=>75,'canvas'=>75);


    # size flags
    const SIZE_FULL=1;
    const SIZE_BIG=2;
    const SIZE_MEDIUM=4;
    const SIZE_THUMB=8;
    const SIZE_SMALL=16;
    const SIZE_ALL=-1;
    
    public function save_images_default($imgData,$callback=NULL, $args=NULL,$sizeFLAG=NULL ){
        $img = \WideImage::load(dirname(__FILE__).'../../../../'.$imgData['path'].$imgData['name'].'.'.$imgData['ext']);
        $Content=new \Core\ContentFactory();
        if($sizeFLAG!=NULL){      
                    if($sizeFLAG & self::SIZE_THUMB){                
                        $img->resize($this->size_thumb['canvas'])
                            ->resizeCanvas($this->size_thumb['width'],  $this->size_thumb['height'],'center',0)
                            ->autoCrop(0,0,1,0)
                            ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'thumbs')['href'].$imgData['name'].'.jpg');
                    }
                     if($sizeFLAG & self::SIZE_SMALL){
                        $img->resize($this->size_small['canvas'])
                            ->resizeCanvas($this->size_small['width'],  $this->size_small['height'],'center',0)
                            ->autoCrop(0,0,1,0)
                            ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'small')['href'].$imgData['name'].'.jpg');
                    }
                    if($sizeFLAG & self::SIZE_BIG){
                        $img->resize($this->size_big['canvas'])
                            ->resizeCanvas($this->size_big['width'],  $this->size_big['height'],'center',0)
                            ->autoCrop(0,0,1,0)
                            ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'big')['href'].$imgData['name'].'.jpg');
                    }
                    if($sizeFLAG & self::SIZE_MEDIUM){
                        $img->resize($this->size_medium['canvas'])
                            ->resizeCanvas($this->size_medium['width'],  $this->size_medium['height'],'center',0)
                            ->autoCrop(0,0,1,0)
                            ->saveToFile($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'medium')['href'].$imgData['name'].'.jpg');
                    }
                    if(!($sizeFLAG & self::SIZE_FULL) || !($sizeFLAG & self::SIZE_ALL)){                
                        unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'fullsize')['href'].$imgData['name'].'.jpg');
                    }

            if(is_array($args)){
                array_unshift($args, $imgData);
            }
            else {
                $args=array();
                $args[0]=$imgData;
            }
            if(!empty($callback)){ 
                if(is_callable($callback)){
                    $imgData=call_user_func_array($callback, $args);
                }
            } 


        }
    }
}

?>
