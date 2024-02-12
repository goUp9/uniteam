<?php
namespace Modules\AdminPanel;
class LoginForm extends \Modules\Form{
    
    function html_form($args) {
        $btn=$this->Kernel->Content->insert_asset('img', 'login_btn');
        $html=$this->Kernel->Forms->startForm($this->formName, $this->formAction)
                ->closeTag()
                
                ->label('Login:')
                ->createInput('text')
                ->addName('username')
                ->closeTag()
                
                ->label('Password')
                ->createInput('password')
                ->addName('password')
                ->closeTag()
                
                ->createInput('image')
                ->addSrc($btn['src'])
                ->addAlt($btn['alt'])
                ->addName('submit')
                ->addValue('submit')
                
                ->closeTag()
                ->endForm()
                
                ->get_form();
        return $html;
    }
    
}
