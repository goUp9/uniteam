<?php
namespace Modules\Redirects;
class OldLinks {
    private $url;
    
    private function set_url(){
        $this->url=$_SERVER['REQUEST_URI'];        
    }
    
    public function redirect(){
        $this->set_url();
        switch($this->url){
            case '/index.php?page=socionika&category=types':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/socionics-types/');
                break;
            case '/index.php?page=socionika&category=basics':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/socionics-basics/');
                break;
            case '/index.php?page=socionika&category=quadras':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/socionics-quadras/');
                break;
            case '/index.php?page=socionika&category=relations':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/socionics-relations/');
                break;
            case '/index.php?page=test':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/online-tests/');
                break;
            case '/index.php?page=articles':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/articles/');
                break;
            case '/index.php?page=humour':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/humour/');
                break;
            
            case '/index.php?page=gallery':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/media/');
                break;
            case '/index.php':
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: http://esocionika.com/');
                break;            
        }
        if(strpos($this->url, '&type=')!==FALSE){
            $el=$this->get_dynamic_url($this->url);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://esocionika.com/socionics-type/'.$el.'/');
        }
        else if(strpos($this->url, '&relation=')!==FALSE){
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://esocionika.com/socionics-relations/');
        }
        else if(strpos($this->url, '&article=')!==FALSE){
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://esocionika.com/articles/');
        }
        else if(strpos($this->url, '&quadra=')!==FALSE){
            $el=$this->get_dynamic_url($this->url);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://esocionika.com/socionics-quadra/'.$el.'/');
        }
        else if(strpos($this->url, '&basics=')!==FALSE){
            $el=$this->get_dynamic_url($this->url);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://esocionika.com/socionics-basics/step-'.$el.'/');
        }
    }
    
    private function get_dynamic_url($url){
        $bits=explode('&',$url);
        $bits=  array_pop($bits);
        $bits=explode('=', $bits);
        return $bits[1];
    }
}

?>
