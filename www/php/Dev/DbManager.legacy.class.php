<?php
namespace Dev;
class DbManager {
    protected $pathDbConfig='/../../config/db.json';
    
    protected $pathDistMysql='/../../dist/mysql/';
    
    public function __construct(\Doctrine\ORM\EntityManager $entityManager){ 
        $this->entityManager=$entityManager;
    }
    
    function save_db_config($dbConfigData){
        $dbConfig=json_encode(array('mysql'=>$dbConfigData),JSON_PRETTY_PRINT);
        $handle=fopen(dirname(__FILE__).$this->pathDbConfig, 'w');
        fwrite($handle, $dbConfig);
    }
    
    # function creates a new table on the database 
    # the table data is taken from the CWD destributive mysql exports
    public function create_db_table_from_dist($mysql_file){        
        \Core\Utils::import_mysql(dirname(__FILE__).$this->pathDistMysql.'/'.$mysql_file, $this->entityManager);
    }
    
}

?>
