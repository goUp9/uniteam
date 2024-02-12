<?php
namespace Modules\PicturesManager;
class Forms extends \Modules\Forms {
    
    protected $formName='PicturesManager';
    
    public function upload_form(){
        $Forms=new \Core\Htmlforms();
        $form=$Forms->startForm($this->formName, CURRENT_PAGE, 'POST', true)
                ->createInput('file')
                ->addName('file')
                ->addId('file')
                ->closeTag()
                ->createInput('submit')
                ->addId('submit')
                ->addName('submit')
                ->addValue('submit')
                ->closeTag()
                ->endForm()
                ->get_form();
        return $form;        
    }
    
    public function upload_multiple_form(){
        $Forms=new \Core\Htmlforms();
        $Request=new \Core\Request();        
        if (isset($Request->post['ajax'])&& $Request->post['ajax']){
            $Forms->startForm($this->formName, '%%action%%', 'POST', true);
        }
        else {
            $Forms->startForm($this->formName, CURRENT_PAGE, 'POST', true);
        }
                $Forms->closeTag()
                ->createInput('file')
                ->addName('file[]')
                ->addId('file')
                ->addMultiple(true)
                ->closeTag();
        if (isset($Request->post['ajax'])&& $Request->post){
            $Forms->createInput('hidden')
                    ->addName('id')
                    ->addId('id')
                    ->addValue('%%id%%')
                    ->closeTag();
        }                
        $form=$Forms->createInput('submit')
                ->addId('submit')
                ->addName('submit')
                ->addValue('submit')
                ->closeTag()
                ->endForm()
                ->get_form();
        return $form;        
    }
    
}
