<?php
namespace Bundle\Controllers\Index;
class HomeForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');

        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
//                ->addNgSubmit("l.submit()")
                ->closeTag();    
        
        $this->Kernel->Forms->addCustom('<angucomplete id="whatquery"
              placeholder="e.g. hairdresser, dry cleaner"
              pause="100"
              selectedobject="whatquery"
              url="'.LINKS_PRE.'ajax/search-what/?search_query="
              datafield="results"
              titlefield="tag" 
              minlength="1"');
        
        if(isset($this->Kernel->Session->access->whatSearch)){
            $initTags=$this->get_initial_values();
            $this->Kernel->Forms->addCustom(' initialvalue="'.$initTags.'" ');
        }
        $this->Kernel->Forms->addCustom('inputclass="form-control form-control-small"></angucomplete>');                 
        
        $this->Kernel->Forms->createInput('submit')
                ->addName('submit')
                ->addValue('Ask')
                ->addNgClick('what.ask()')
                ->addId('btn_ask')
                ->closeTag();
        $this->Kernel->Forms->createInput('submit')
                ->addName('submit')
                ->addValue('Supply')
                ->addNgClick('what.supply()')
                ->addId('btn_supply')
                ->closeTag(); 
        
        
        return $this->Kernel->Forms->endForm()                
                ->get_form();        
    
    }
    
    private function get_initial_values(){        
        $initTags='';
        foreach($this->Kernel->Session->access->whatSearch as $tag){
            if($tag!==""){
                $initTags.=trim($tag).',';
            }
        }
        $init=$initTags;
        
        return $init;
    }
}
