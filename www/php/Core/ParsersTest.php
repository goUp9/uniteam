<?php
class ParsersTest extends PHPUnit_Framework_TestCase{
    public function testsimple_replacement(){
        $content="<form>"
                . "<input type='text'/>"
                . "%1%"
                . "%2%"                
                . "</form>";
        $replacementArray=array('<select><option>test option</option></select>',"<input type='email'/>");
        $result=\Core\Parsers::simple_replacement($content, $replacementArray);
         $this->expectOutputString("<form>"
                . "<input type='text'/>"
                . "<select><option>test option</option></select>"
                . "<input type='email'/>"                
                . "</form>");
        echo $result;
    }
}
