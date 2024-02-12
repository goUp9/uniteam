<?php
namespace Bundle\Controllers\MyUin;
class ProfileForm extends \Modules\Form{
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');
        $userData=$args[0];
        
        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("s.submit()")
                ->closeTag();   
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('username*');
        $this->Kernel->Forms->createInput("text")
                ->addName("username")
                ->addNgModel("s.formData.username")
                ->addNgInit("s.formData.username='".$userData['username']."'")
                ->addPlaceholder("Username")
                ->addRequired()
                ->closeTag();      
        $this->Kernel->Forms->addCustom('</div>');
                
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('first name*');
        $this->Kernel->Forms->createInput("text")
                ->addName("fname")
                ->addPlaceholder("First Name")
                ->addNgModel("s.formData.fName")
                ->addNgInit("s.formData.fName='".$userData['fName']."'")
                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('last name*');
        $this->Kernel->Forms->createInput("text")
                ->addName("lname")
                ->addPlaceholder("Last Name")
                ->addNgModel("s.formData.lName")
                ->addNgInit("s.formData.lName='".$userData['lName']."'")
                ->addRequired()
                ->closeTag();        
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->Label('email address*');
        $this->Kernel->Forms->createInput("email")
                ->addName("email")
                ->addNgModel("s.formData.email")
                ->addNgInit("s.formData.email='".$userData['email']."'")
                ->addPlaceholder("Email")
                ->addRequired()
                ->closeTag();   
        $this->Kernel->Forms->Label('phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("phone")
                ->addNgModel("s.formData.phone")
                ->addCustom(" international-phone-number ")
                ->addNgInit("s.formData.phone='".$userData['phone']."'")
                ->addCustom(" international-phone-number ")
                ->addCustom(" national-mode=\"false\" ")
                ->addCustom(" number-type=\"FIXED_LINE\" ")
                ->closeTag();
        $this->Kernel->Forms->Label('mobile phone');
        $this->Kernel->Forms->createInput("text")
                ->addName("mobile")
                ->addNgModel("s.formData.mobile")
                 ->addCustom(" international-phone-number ")
                ->addNgInit("s.formData.mobile='".$userData['mobile']."'")
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
                ->addNgInit("s.formData.address='".$userData['address']."'")
//                ->addRequired()
                ->closeTag();        
        $this->Kernel->Forms->Label('city/town');
        $this->Kernel->Forms->createInput("text")
                ->addName("city")
                ->addPlaceholder("City/town")
                ->addNgModel("s.formData.city")
                ->addNgInit("s.formData.city='".$userData['city']."'")
//                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->Label('state/province');
        $this->Kernel->Forms->createInput("text")
                ->addName("state")
                ->addPlaceholder("State/province")
                ->addNgModel("s.formData.state")
                ->addNgInit("s.formData.state='".$userData['state']."'")
                ->closeTag();
        $this->Kernel->Forms->Label('country');
        $this->Kernel->Forms->createInput("text")
                ->addName("country")
                ->addPlaceholder("Country")
                ->addNgModel("s.formData.country")
                ->addNgInit("s.formData.country='".$userData['country']."'")
                ->addCustom(" country-select ")
                ->closeTag();
        $this->Kernel->Forms->Label('postal/ZIP code');
        $this->Kernel->Forms->createInput("text")
                ->addName("zip")
                ->addPlaceholder("Postal/ZIP code")
                ->addNgModel("s.formData.zip")
                ->addNgInit("s.formData.zip='".$userData['zip']."'")
//                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->addCustom('<div class="wrap">');         
        $this->Kernel->Forms->Label('Paypal account (for Suppliers and Advisers - to receive payments)');
        $this->Kernel->Forms->createInput("text")
                ->addName("paypal")
                ->addPlaceholder("paypal account name")
                ->addNgModel("s.formData.paypal");
                if(isset($userData['paypal'])&&!empty($userData['paypal'])){
                    $this->Kernel->Forms->addNgInit("s.formData.paypal='".$userData['paypal']."'");
                }
                $this->Kernel->Forms->closeTag();
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
