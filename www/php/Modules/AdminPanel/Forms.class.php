<?php
namespace Modules\AdminPanel;
class Forms extends \Modules\Modules implements \Modules\Forms{
    private $formName;
    private $formAction;
            
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
    
    function login_form(){
        $btn=$this->Kernel->Content->insert_asset('img', 'login_btn');
        $html=$this->Kernel->Forms->startForm('form-login', LINKS_PRE.'admin/')
                ->closeTag()
                
                ->label('Login:')
                ->createInput('text')
                ->addName('login')
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
    
    public function default_edt_text($content,$id=NULL){
        $form=$this->Kernel->Forms->startForm($this->formName, $this->formAction)
                ->closeTag()
        
            ->startTextarea()
            ->addId('text')
            ->addName('text')
            ->addClass('ck')
            ->closeTag()                
            ->addCustom($content)
            ->endTextarea()
                
            ->createInput('hidden')
            ->addName('id')
            ->addValue($id)
            ->addId('id')
            ->closeTag()
                
            ->createInput('submit')
            ->addId('submit')
            ->addValue('submit')
            ->addName('submit')
            ->closeTag()
                
            ->endForm()
            ->get_form();
        
        return $form;
    }
}

?>
