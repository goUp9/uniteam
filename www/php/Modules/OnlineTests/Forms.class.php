<?php
namespace Modules\OnlineTests;
class Forms extends \Modules\Forms {
    
    public function test_page_form($testData){ 
        $Forms=new \Core\Htmlforms(); 
        $forms=array();
        $y=0;
        foreach($testData->pages->page as $pages){
            $i=0;
            foreach($pages->answer as $answer){
                if($i===0){
                     $Forms->addCustom('<div class="onlineTest-answer" ng-init="ot.answers['.$y.']=\''.$answer['value'].'\'">');               
                }
                else {
                    $Forms->addCustom('<div class="onlineTest-answer">');
                }
                $Forms->createInput('radio')
                        ->addName($pages->question['value'])
                        ->addCustom(' ng-model="ot.answers['.$y.']" ')
                        ->addValue($answer['value']);
                        
                        $Forms->closeTag()
                        ->label($answer)
                        ->addCustom('</div>'); 
                $i++;
            }
            $form=$Forms->get_form();                
            array_push($forms, $form);
            $y++;
        }        
        return $forms;
    }
}

?>
