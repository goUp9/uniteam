<?php
namespace Modules\TextsManager;
class Forms{
    
    public function text($itemData=NULL){
        $Forms=new \Core\Htmlforms();
        $Forms->startForm('default-text-manager', CURRENT_PAGE)
                ->addCustom('class="admin--standard"')
                ->closeTag();
                
        if (isset($itemData)&&!empty($itemData)){
            $Forms->createInput('hidden')
                    ->addName('id')
                    ->addValue($itemData['id'])
                    ->closeTag();
        }      
        
        $Forms->label("title")
                ->createInput('text')
                    ->addName('title')
                    ->addValue($itemData['title']);
                    if($itemData!==NULL){
                        $Forms->addDisabled(true);
                    }
                    $Forms->closeTag();
        
        $Forms->label('Text:')
                ->startTextarea()
                ->addName('text')
                ->addPlaceholder('Your text')
                ->addClass('ckeditor')
                ->closeTag();
        if(isset($itemData)&&!empty($itemData['text'])){
            $Forms->addCustom($itemData['text']);
        }
        $Forms->endTextarea();
        
        $Forms->createInput('submit')
                ->addName('save')
                ->addValue('save')
                ->closeTag();
        
        $Forms->endForm();
        
        $form=$Forms->get_form();
        return $form;
    }
    
//    public function general_texts_select(){
//        $texts=\Core\Utils::read_json('php/modules/TextManager/data/general_texts');
//        $Forms=new \Core\Htmlforms();
//        $Forms->startSelect();
//        foreach ($texts['texts'] as $item){
//            $Forms->startOption()
//                    ->addName($item['title'])
//                    ->addValue($item['repo'])
//                    ->closeTag();
//            $Forms->addCustom($item['title'])
//                    ->endOption();
//        }
//        $forms=$Forms->endSelect(); 
//        return $forms;
//    }
    
}

?>
