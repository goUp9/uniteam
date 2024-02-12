<?php
namespace Bundle\Controllers\Users;
class RestorePasswordForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');

        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("rp.submit()")
                ->closeTag();        
                      
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->createInput("email")
                ->addName("email")    
                ->addPlaceholder("email")
                ->addNgModel("rp.formData.email")
                ->addRequired()
                ->closeTag();
        
        $this->Kernel->Forms->addCustom('</div>');
                
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
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
