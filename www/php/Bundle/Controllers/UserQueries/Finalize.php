<?php
namespace Bundle\Controllers\UserQueries;
class Finalize extends \Modules\Modules{
    
    function main(){        
        $Login=new \Bundle\Controllers\Users\Login($this->Kernel); 
        $type=$this->Kernel->Request->get['type'];
        if($Login->is_logged()){            
            switch ($type) {
                case 'ask':
                        $link=$this->Kernel->Content->insert_asset('link','myuin__asking')['href'];
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$link);                        
                    break;
                case 'supply':
                        $link=$this->Kernel->Content->insert_asset('link','myuin__supplying')['href'];
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$link);
                    break;
                case 'advice':
                        $link=$this->Kernel->Content->insert_asset('link','myuin__advising')['href'];
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$link);
                    break;
                default:
                        $link=$this->Kernel->Content->insert_asset('link','myuin__asking')['href'];
                        header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$link);
                    break;
            }            
        }
        else {
            $link=$this->Kernel->Content->insert_asset('link','registration')['href'];
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$link);
        }
    }
    
}
