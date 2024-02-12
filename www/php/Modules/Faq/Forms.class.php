<?php
namespace Modules\Faq;
class Forms extends \Modules\Forms{

    public function faq($data=NULL){
        $Forms= new \Core\Htmlforms();
        $Forms->startForm($this->formName, CURRENT_PAGE)
                ->addClass('admin--standard')
            ->closeTag()
                
            ->createInput('text')
            ->addName('question')
            ->addPlaceholder('question')
            ->addId('question');
            if (isset($data)&&!empty($data)){
                $Forms->addValue($data['question']);
            }      
            $Forms->closeTag();
            
            if (isset($data)&&!empty($data)){
                $Forms->createInput('hidden')
                        ->addName('id')
                        ->addValue($data['id'])
                        ->closeTag();
            } 
            
        $Forms->addCustom('<div class="checkbox-wrap">')
                ->createInput('checkbox')
                ->addName('status')
                ->addId('status')
                ->addValue('1');
        if (isset($data)&&!empty($data)&&$data['status']===1){
            $Forms->addChecked();
        }    
        $Forms->closeTag();
        $Forms->label('Published', 'status')
        ->addCustom('</div>');
                    
            $Forms->addCustom('<div text-angular>')
            ->startTextarea()
            ->addId('answer')
            ->addName('answer')
            ->addClass('ck')
            ->closeTag() ;
            if (isset($data)&&!empty($data)){
                $Forms->addCustom($data['answer']);
            }    
        $Forms->endTextarea()
                ->addCustom('</div>');
        
       
        
                
        $form=$Forms->createInput('submit')
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
