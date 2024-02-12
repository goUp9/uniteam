<?php
namespace Modules\Faq;
class AskUsPanelForm {
     public function create_askPanel_form(){
        $forms=new \Core\Htmlforms();
        $form=$forms->startForm('askPanel-form')                              
              ->closeTag()
              ->createInput('text')
              ->addCustom('p-holder')
              ->addPlaceholder('E.g. Can my Socionics Type change?')
              ->addName('field-ask-question')
              ->addId('field-ask-question')
              ->closeTag()
              ->endForm()
              ->get_form();
        return $form;
    }
}

?>
