<?php
/**
 * Description of DB_Loader
 * @package: Twig
 * @author: Anastasia Sitnina
 * @version: 1.0
 */
class Twig_Loader_DB implements Twig_LoaderInterface, Twig_ExistsLoaderInterface{
    protected $table;
    
    public function __construct($table_name) {
        $this->table=$table_name;
    }

     /**
     * Gets the source code of a template, given its name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The template source code
     */
    function getSource($name){
        return $this->getValue($name);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The cache key
     */
    function getCacheKey($name){        
        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    function isFresh($name, $time){
        return true;
    }
    
    function getValue($name){
        $DB=new getDB();        
        $template=$DB->get_template($name, $this->table);        
        return $template;
    }
    
    public function exists($name){
        return true;
    }
}

?>
