<?php
namespace Modules;
trait Data {
    
    public function set_pagination_pages($data,$currentPage){
        $pages=array();
        for($i=1; $i<=$data;$i++){
            $pages[$i]['num']=$i;
            # check if it is a current page
            if($currentPage==$i){
                $pages[$i]['selected']=TRUE;
            }
            else {
                $pages[$i]['selected']=FALSE;
            }
        }
        return $pages;
    }
    
}

?>
