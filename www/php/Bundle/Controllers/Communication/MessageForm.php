<?php
namespace Bundle\Controllers\Communication;
class MessageForm extends \Modules\Form{
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');
        
        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("m.submit()")
                ->closeTag();   
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('To:');      
        $this->Kernel->Forms->addCustom('<angucomplete id="recipient"
              placeholder="Search contacts"
              pause="100"
              selectedobject="recipient"
              url="'.LINKS_PRE.'ajax/get-contacts/?search_query="
              datafield="results" ');
        
        if(!empty($this->Kernel->Request->get['username'])){
            $this->Kernel->Forms->addCustom(' initialvalue="'.$this->Kernel->Request->get['username'].'" ');
        }
        
         $this->Kernel->Forms->addCustom(' titlefield="username" 
              minlength="1" 
              inputclass="form-control form-control-small"/>');
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom(' <div text-angular ng-model="m.msgData.msg"></div> ');

        
        $this->Kernel->Forms->addCustom('<div class="wrap btns">');
        $this->Kernel->Forms->createInput('image')
                ->addName('submit')
                ->addValue('submit')
                ->addSrc($btn['src'])
                ->addAlt($btn['alt'])
                ->closeTag();
        $this->Kernel->Forms->addCustom('</div>');        
        
        
        return $this->Kernel->Forms->endForm()                
                ->get_form();
               
    
    }
}
