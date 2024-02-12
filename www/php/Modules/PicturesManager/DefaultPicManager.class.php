<?php
namespace Modules\PicturesManager;
class DefaultPicManager extends Definitions{ 
    use \Modules\AdminPanel\Editing;
    public function upload_images(){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->Kernel);
        $Unlogged->unlogged();
        $Request=new \Core\Request();
        if(!empty($Request->files)){
            $DefaultPic=new \Modules\PicturesManager\DefaultPictures($this->Kernel->entityManager);
//            $DefaultPic->repository="Bundle\Doctrine\Entities\Pictures";  
            $DefaultPic->size_thumb=array('width'=>150,'height'=>150,'canvas'=>150);
            $DefaultPic->upload(null, null, \Modules\PicturesManager\ImageLoader::SIZE_FULL|\Modules\PicturesManager\ImageLoader::SIZE_THUMB|\Modules\PicturesManager\ImageLoader::SIZE_BIG|\Modules\PicturesManager\ImageLoader::SIZE_SMALL|\Modules\PicturesManager\ImageLoader::SIZE_MEDIUM);
        }
        
        #create content object
        $Content=new \Core\ContentFactory();
        $this->Kernel->Content->init_assets();
        
        #create meta object
        $Meta=new \Core\MetadataFactory();
        $this->Kernel->Meta->add_assets();
        
        /* create delete item link */
        $this->Kernel->Content->set_data('pic-manager', 'delLink');
        
        $PicturesManager= new \Modules\PicturesManager\Forms();
        $this->Kernel->Content->set_form($PicturesManager->upload_multiple_form(), 'picManager');
        
        $DefaultPicMngr=new \Modules\PicturesManager\DefaultPictures($this->Kernel);
        $pictures=$DefaultPicMngr->get_all_pictures();

        $this->Kernel->Content->set_data($pictures, 'pictures');

        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    public function delete_callback(){
        $Unlogged=new \Bundle\Controllers\Admin\AdminLogin($this->Kernel);
        $Unlogged->unlogged();
        $Request=new \Core\Request();
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $picData=$DataMngr->get_item_by_id($this->repository, $Request->post['id']);
        
        
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'thumbs')['href'].$picData['picture'])){
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'thumbs')['href'].$picData['picture']);
        }
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'small')['href'].$picData['picture'])){
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'small')['href'].$picData['picture']);
        }
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'medium')['href'].$picData['picture'])){
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'medium')['href'].$picData['picture']);
        }
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'big')['href'].$picData['picture'])){
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'big')['href'].$picData['picture']);
        }
        if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'fullsize')['href'].$picData['picture'])){
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->Kernel->Content->insert_asset('path', 'fullsize')['href'].$picData['picture']);
        }
        
        
    }
}

?>
