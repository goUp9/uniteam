<?php
namespace Modules;
interface Admin {
    
    public function listing($page);
    
    public function editing($id);
    
    public function delete();
    
}
