<?php
namespace Bundle\Controllers\Users;
class LoginForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');

        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("l.submit()")
                ->closeTag();        
                      
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->createInput("text")
                ->addName("username")    
                ->addPlaceholder("Username")
                ->addNgModel("l.formData.username")
                ->addRequired()
                ->closeTag();
        
        $this->Kernel->Forms->createInput("password")
                ->addName("password")    
                ->addPlaceholder("Password")
                ->addNgModel("l.formData.password")
                ->addRequired()
                ->closeTag();
        
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');        
        $this->Kernel->Forms->createInput('checkbox')
                ->addName('remember_me')
                ->addNgModel("l.formData.remember_me")
                ->closeTag();
        $this->Kernel->Forms->Label('remember me');
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
