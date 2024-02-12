<?php
namespace Bundle\Controllers\Users;
class RegistrationForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_onWhite_btn');
        
        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addCustom(' ng-hide="success.flag"')
                ->addNgSubmit("s.submit()")
                ->closeTag();   
        
        $this->Kernel->Forms->addCustom('<div class="wrap center">');
        $this->Kernel->Forms->Label('username*');
        $this->Kernel->Forms->createInput("text")
                ->addName("username")
                ->addNgModel("s.formData.username")
                ->addPlaceholder("Username")
                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('password*');
        $this->Kernel->Forms->createInput("password")
                ->addName("password")    
                ->addPlaceholder("Password")
                ->addNgModel("s.formData.password")
                //->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('confirm password*');
        $this->Kernel->Forms->createInput("password")
                ->addName("password2")
                ->addNgModel("s.formData.password2")
                ->addPlaceholder("Confirm Password")
                //->addRequired()
                ->closeTag();        
        $this->Kernel->Forms->addCustom('</div>');
                
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('first name*');
        $this->Kernel->Forms->createInput("text")
                ->addName("fname")
                ->addPlaceholder("First Name")
                ->addNgModel("s.formData.fName")
                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('last name*');
        $this->Kernel->Forms->createInput("text")
                ->addName("lname")
                ->addPlaceholder("Last Name")
                ->addNgModel("s.formData.lName")
                ->addRequired()
                ->closeTag();        
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap center">');
        $this->Kernel->Forms->Label('email address*');
        $this->Kernel->Forms->createInput("email")
                ->addName("email")
                ->addNgModel("s.formData.email")
                ->addPlaceholder("Email")
                ->addRequired()
                ->closeTag();   
        $this->Kernel->Forms->Label('phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("phone")
                ->addNgModel("s.formData.phone")
                ->addCustom(" international-phone-number ")
                ->addCustom(" national-mode=\"false\" ")
                ->addCustom(" number-type=\"FIXED_LINE\" ")
                ->closeTag();
        $this->Kernel->Forms->Label('mobile phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("mobile")
                ->addNgModel("s.formData.mobile")
                ->addCustom(" international-phone-number ")
                ->addCustom(" national-mode=\"false\" ")
//                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">'); 
        $this->Kernel->Forms->Label('address');
        $this->Kernel->Forms->createInput("text")
                ->addName("address")
                ->addPlaceholder("Address")
                ->addNgModel("s.formData.address")
//                ->addRequired()
                ->closeTag();        
        $this->Kernel->Forms->Label('city/town');
        $this->Kernel->Forms->createInput("text")
                ->addName("city")
                ->addPlaceholder("City/town")
                ->addNgModel("s.formData.city")
//                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('state/province');
        $this->Kernel->Forms->createInput("text")
                ->addName("state")
                ->addPlaceholder("State/province")
                ->addNgModel("s.formData.state")
                ->closeTag();
        $this->Kernel->Forms->Label('country');
        $this->Kernel->Forms->createInput("text")
                ->addName("country")
                ->addPlaceholder("Country")
                ->addCustom(" country-select ")
                ->addNgModel("s.formData.country")
                //->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('postal/ZIP code');
        $this->Kernel->Forms->createInput("text")
                ->addName("zip")
                ->addPlaceholder("Postal/ZIP code")
                ->addNgModel("s.formData.zip")
//                ->addRequired()
                ->closeTag();
            $this->Kernel->Forms->addCustom('<div>');
                $this->Kernel->Forms->Label('I would like to subscribe to UinTeam newsletters');
                $this->Kernel->Forms->createInput('checkbox')
                        ->addName('newsletterSubscribed')
                        ->addNgModel('s.formData.newsletterSubscribed')
                        ->closeTag();
            $this->Kernel->Forms->addCustom('</div>');
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
