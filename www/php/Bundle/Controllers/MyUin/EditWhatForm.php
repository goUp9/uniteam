<?php
namespace Bundle\Controllers\MyUin;
class EditWhatForm extends \Modules\Form{
    
    function html_form($args) {

        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("what.save()")
                ->closeTag();    
        
        $this->Kernel->Forms->addCustom('<angucomplete id="whatquery"
              placeholder="e.g. hairdresser, dry cleaner"
              pause="100"
              selectedobject="whatquery"
              url="'.LINKS_PRE.'ajax/search-what/?search_query="
              datafield="results"
              titlefield="tag" 
              minlength="1"');        
        if(!empty($args)){
            $initTags='';
            foreach($args[0]['whats'] as $whats){
                $initTags.=$whats['tag']['tag'].',';
            }
            
            $this->Kernel->Forms->addCustom(' initialvalue="'.$initTags.'" ');
        }
        $this->Kernel->Forms->addCustom('inputclass="form-control form-control-small"></angucomplete>');   
        
        $this->Kernel->Forms->createInput('hidden')
                ->addName('query_id')
                ->addNgModel('what.formData.idQuery')
                ->addNgInit('what.formData.idQuery='.$this->Kernel->Request->get['query_id'])
                ->addValue($this->Kernel->Request->get['query_id'])
                ->closeTag();
        
        $this->Kernel->Forms->createInput('submit')
                ->addName('submit')
                ->addValue('Save Changes')
                ->addId('btn_save')
                ->closeTag();
        
        
        return $this->Kernel->Forms->endForm()                
                ->get_form();        
    
    }
    
}
