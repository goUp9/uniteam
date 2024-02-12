<?php
namespace Core;
class Server {
    public $root;
    public $host;


    public function __construct() {
        if(isset($_SERVER)&&isset($_SERVER['DOCUMENT_ROOT'])){
            $this->root=$_SERVER['DOCUMENT_ROOT'];
        }
        if(isset($_SERVER)&&isset($_SERVER['HTTP_HOST'])){
            $this->host=$_SERVER['HTTP_HOST'];
        }
    }
}

?>
