<?php
namespace Core;
class Parsers {
    
    public static function simple_replacement($content,$replacementArray){        
        foreach($replacementArray as $key=>$r){            
            $content=str_replace('%'.($key+1).'%', $r, $content);
        }
        return $content;
    }
    
}
