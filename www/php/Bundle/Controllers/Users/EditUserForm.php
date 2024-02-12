<?php
namespace Bundle\Controllers\Users;
class EditUserForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');
        
        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("u.edit()")
                ->closeTag();   
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('username');
        $this->Kernel->Forms->createInput("text")
                ->addName("username")
                ->addNgModel("u.editItem.username")                
                ->addPlaceholder("Username")                
                ->closeTag();      
        $this->Kernel->Forms->addCustom('</div>');
                
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('first name');
        $this->Kernel->Forms->createInput("text")
                ->addName("fname")
                ->addPlaceholder("First Name")
                ->addNgModel("u.editItem.fName")                
                ->closeTag();
        $this->Kernel->Forms->Label('last name');
        $this->Kernel->Forms->createInput("text")
                ->addName("lname")
                ->addPlaceholder("Last Name")
                ->addNgModel("u.editItem.lName")               
                
                ->closeTag();        
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('email address');
        $this->Kernel->Forms->createInput("email")
                ->addName("email")
                ->addNgModel("u.editItem.email")
                ->addPlaceholder("Email")                
                ->closeTag();   
        $this->Kernel->Forms->Label('phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("phone")
                ->addNgModel("u.editItem.phone")
                ->addCustom(" international-phone-number ")
                ->closeTag();
        $this->Kernel->Forms->Label('mobile phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("mobile")
                ->addNgModel("u.editItem.mobile")
                ->addCustom(" international-phone-number ")                
                ->closeTag();
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">'); 
        $this->Kernel->Forms->Label('address');
        $this->Kernel->Forms->createInput("text")
                ->addName("address")
                ->addPlaceholder("Address")
                ->addNgModel("u.editItem.address")
                
                ->closeTag();        
        $this->Kernel->Forms->Label('city/town');
        $this->Kernel->Forms->createInput("text")
                ->addName("city")
                ->addPlaceholder("City/town")
                ->addNgModel("u.editItem.city")
                
                ->closeTag();
        $this->Kernel->Forms->Label('state/province');
        $this->Kernel->Forms->createInput("text")
                ->addName("state")
                ->addPlaceholder("State/province")
                ->addNgModel("u.editItem.state")
                ->closeTag();
        $this->Kernel->Forms->Label('country');
        $this->Kernel->Forms->createInput("text")
                ->addName("country")
                ->addPlaceholder("Country")
                ->addNgModel("u.editItem.country")                
                ->closeTag();
        $this->Kernel->Forms->Label('postal/ZIP code');
        $this->Kernel->Forms->createInput("text")
                ->addName("zip")
                ->addPlaceholder("Postal/ZIP code")
                ->addNgModel("u.editItem.zip")                
                ->closeTag();
        $this->Kernel->Forms->addCustom('</div>');
        
        
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
