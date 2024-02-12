<?php
namespace Modules\Gallery;
class GalleryItem extends \Modules\DataManager{
    public $repository='Bundle\Doctrine\Entities\Gallery';
    
    public function get_gallery_item($id){
        return $this->get_item_by_id($this->repository,$id);
    }
    
    public function get_gallery_item_w_folders($id){
        $QueryBuilder=$this->entityManager->createQueryBuilder();        
        $QueryBuilder->select('foldersPictures','pictures','folders')
            ->from('Bundle\Doctrine\Entities\FoldersPictures', 'foldersPictures')
            ->leftJoin('foldersPictures.idPicture','pictures') 
            ->leftJoin('foldersPictures.idFolder','folders')
            ->where('foldersPictures.idPicture='.$id)
        ;
        $q = $QueryBuilder->getQuery();

        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY); 
        if(empty($data)){
            $data=$this->get_gallery_item($id); 
        }
        return $data;
    }
    
    public function get_items_by_folder($idFolder,$idPicture=NULL){
        $QueryBuilder=$this->entityManager->createQueryBuilder();        
        $QueryBuilder->select('foldersPictures','pictures','folders')
            ->from('Bundle\Doctrine\Entities\FoldersPictures', 'foldersPictures')
            ->leftJoin('foldersPictures.idPicture','pictures')
            ->leftJoin('foldersPictures.idFolder','folders')
            ->where('foldersPictures.idFolder='.$idFolder);
            if(isset($idPicture)){
                $QueryBuilder->andWhere('foldersPictures.idPicture='.$idPicture);
            }
        $q = $QueryBuilder->getQuery();

        $data= $q->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY); 
        return $data;
    }
    
    public function clear_item_from_folders($idPicture){
        $QueryBuilder=$this->entityManager->createQueryBuilder();        
        $QueryBuilder->select('foldersPictures')
            ->from('Bundle\Doctrine\Entities\FoldersPictures', 'foldersPictures')
            ->where('foldersPictures.idPicture='.$idPicture);
        $q = $QueryBuilder->getQuery();

        $data= $q->getResult(); 
        foreach($data as $entry){
            $this->entityManager->remove($entry);
            $this->entityManager->flush();
        }
        return $data;
    }
    
    public function delete_item($id){
        $item=$this->get_gallery_item($id);
        $ImgLoader=new ImageLoader();
        if(isset($item['picture'])){
            $item['pic']=$item['picture'];
        }
        $Content=new \Core\ContentFactory();
        if(isset($item['pic'])){
            if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_fullsize')['href'].$item['pic'])){
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_fullsize')['href'].$item['pic']);
            }
            if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_big')['href'].$item['pic'])){
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_big')['href'].$item['pic']);
            }
            if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_medium')['href'].$item['pic'])){
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_medium')['href'].$item['pic']);
            }
            if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_small')['href'].$item['pic'])){
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_small')['href'].$item['pic']);
            }
            if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_thumbs')['href'].$item['pic'])){
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_thumbs')['href'].$item['pic']);
            }
        }
        $this->delete_item_by_id($this->repository,$id);
    }
    
    public function create_item($data){
        return $this->create_new_item($this->repository,$data);
    }
    
    public function set_gallery_item($id,$data){        
        $item=$this->get_gallery_item($id);
        $Content=new \Core\ContentFactory();
        if(isset($data['picture'])||isset($data['pic'])){
            if(isset($item['picture'])&&isset($data['picture'])){
                $item['pic']=$item['picture'];
                $data['pic']=$data['picture'];
            }
            if(isset($item['pic'])&&$data['pic']!==NULL){
                if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_fullsize')['href'].$item['pic'])){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_fullsize')['href'].$item['pic']);
                }
                if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_big')['href'].$item['pic'])){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_big')['href'].$item['pic']);
                }
                if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_medium')['href'].$item['pic'])){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_medium')['href'].$item['pic']);
                }
                 if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_small')['href'].$item['pic'])){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_small')['href'].$item['pic']);
                }
                if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_thumbs')['href'].$item['pic'])){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/'.$Content->insert_asset('path', 'gallery_thumbs')['href'].$item['pic']);
                }
            }
        }
        return $this->update_item_by_id($id, $this->repository, $data);
    }
    
}

?>
