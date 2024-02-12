<?php
namespace Modules\Gallery;
class Forms extends \Modules\Forms{
    protected $formName='form--gallery';
    protected $textInputs=array('title');
    protected $textAreas=array('text');
    protected $selects=array();
    
//    function item($itemData=NULL){
//        $Forms=new \Core\Htmlforms();
//        $Forms->startForm($this->formName, CURRENT_PAGE,'POST',true)
//                ->closeTag();        
//        $Forms->addCustom($this->create_text_inputs($itemData));
//        $Forms->addCustom($this->create_selects($itemData));
//        $Forms->addCustom($this->create_textareas($itemData));
//        
//        
//        if (isset($itemData)&&!empty($itemData)){
//            $Forms->createInput('hidden')
//                    ->addName('id')
//                    ->addValue($itemData['id'])
//                    ->closeTag();
//        }
//        
//        $Forms->label('Add gallery image:')
//                ->createInput('file')
//                ->addName('pic')
//                ->addId('pic')
//                ->closeTag();
//        
//        $form=$Forms->createInput('submit')
//                ->addId('submit')
//                ->addName('submit')
//                ->addValue('submit')
//                ->closeTag()
//                ->endForm()
//                ->get_form();
//        return $form;
//    }
   
}

?>
