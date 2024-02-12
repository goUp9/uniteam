<?php
class UtilsTest extends PHPUnit_Framework_TestCase{
    public function testread_json(){ 
        $path=  dirname(__FILE__).'/../../config/classes.json';        
        $result=  Core\Utils::read_json($path);
        $this->assertInternalType('array',$result);
    }
    
    public function testpagination(){
        $numberOfItems=50;
        $itemsPerPage=5;
        $currentPageNumber=3;
        $result=Core\Utils::pagination($numberOfItems, $itemsPerPage, $currentPageNumber);
        $this->assertInternalType('array',$result);
        $this->assertEquals(10,$result['totalPages']);
        $this->assertEquals(10,$result['totalPages']);        
    }
    
    public function testfilter_input(){
        $array=array(
            0=>array(
                'test'=>array(
                            0=>'blah&'
                    ),
                'test2'=>'this'
                ),
            1=>'that'
            );
        $result=Core\Utils::filter_input($array);
        $this->assertInternalType('array',$result);
        $this->assertEquals('blah&#38;',$result[0]['test'][0]);
    }
}

?>
