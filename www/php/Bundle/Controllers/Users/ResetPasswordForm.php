<?php
namespace Bundle\Controllers\Users;

class ResetPasswordForm extends \Modules\Form {

    function html_form($args) {
        $btn = $this->Kernel->Content->insert_asset('img', 'submit_btn');

        $this->Kernel->Forms->startForm($this->formName, $this->formAction)
                ->addNgSubmit('rp.submit()')
                ->closeTag();


        $this->Kernel->Forms->addCustom('<div class="wrap">');
        
        $this->Kernel->Forms->label("current password");
        $this->Kernel->Forms->createInput("password")
                ->addName("password")
                ->addPlaceholder("Old Password")
                ->addNgModel("rp.formData.password")
                ->addRequired()
                ->closeTag();

        $this->Kernel->Forms->addCustom('</div>');

        $this->Kernel->Forms->addCustom('<div class="wrap">');
        $this->Kernel->Forms->label("new password");
        $this->Kernel->Forms->createInput("password")
                ->addName("newpassword")
                ->addPlaceholder("New Password")
                ->addNgModel("rp.formData.newpassword")
                ->addRequired()
                ->closeTag();
        $this->Kernel->Forms->label("confirm new password");
        $this->Kernel->Forms->createInput("password")
                ->addName("newpassword2")
                ->addNgModel("rp.formData.newpassword2")
                ->addPlaceholder("Confirm New Password")
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
