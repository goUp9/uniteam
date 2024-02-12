<?php
namespace Modules\Users;
class Forms extends \Modules\Modules implements \Modules\Forms{
    protected $formName;
    protected $formAction;
            
    function set_formName($formName) {
        $this->formName=$formName;
    }
    
    function set_formAction($formAction=NULL) {
        if(isset($formAction)){
            $this->formName=$formAction;
        }
        else {
            $this->formAction=CURRENT_PAGE;
        }
    }
    
    function email($userData){
        $btn=$this->Kernel->Content->insert_asset('img', 'submit_btn');
        
        $this->Kernel->Forms->startForm('form--change-email')                
                ->addNgSubmit('email.submit()')
                ->closeTag();
        
        $this->Kernel->Forms->createInput("email")
                ->addName("email")
                ->addNgInit("email.formData.email='".$userData['email']."'")
                ->addNgModel("email.formData.email")
                ->addRequired()
                ->closeTag();
        
        $this->Kernel->Forms->Forms->createInput('image')
                ->addName('submit')
                ->addSrc($btn['src'])
                ->addAlt($btn['alt'])
                ->closeTag();
        
        return $this->Kernel->endForm()                
                ->get_form();
        
    }
    
    function password(){
        $btn=$this->Kernel->Content->insert_asset('img', 'submit_btn');
        
        $this->Kernel->startForm('form--change-password')                
                ->addNgSubmit('password.submit()')
                ->closeTag();
        
        $this->Kernel->label('Old password');
        $this->Kernel->createInput('password')
                ->addPattern('.{6,40}')
                ->addTitle('6 characters minimum')
                ->addRequired()
                ->addNgModel('password.formData.password')
                ->addName('password')
                ->closeTag();
        
        $this->Kernel->label('New password');
        $this->Kernel->createInput('password')
                ->addPattern('.{6,40}')
                ->addTitle('6 characters minimum')
                ->addName('newPassword')
                ->addNgModel('password.formData.newPassword')
                ->addRequired()                
                ->closeTag();
        
        $this->Kernel->label('Confirm new password');
        $this->Kernel->createInput('password')
                ->addName('newPassword2')
                ->addPattern('.{6,40}')
                ->addTitle('6 characters minimum')
                 ->addNgModel('password.formData.newPassword2')
                ->addRequired()
                ->closeTag();
        
        $this->Kernel->createInput('image')
                ->addName('submit')
                ->addSrc($btn['src'])
                ->addAlt($btn['alt'])
                ->closeTag();
        
        return $this->Kernel->endForm()
                ->get_form();
        
    }
    
}
