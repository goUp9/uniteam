<?php
namespace Modules\Subscription;
class Forms extends Definitions{
    
    public function get_subscription_form(){
        $forms=new \Core\Htmlforms();       
        $btn=$this->Kernel->Content->insert_asset('img', 'subscribeBtn');
        $form=$forms->startForm('subscription')
              ->addCustom('ng-controller="processSubscriptionFormCtrl as pf"')                
              ->addCustom('ng-submit="pf.submit()"')
              ->closeTag()
              ->createInput('email')
              ->addName('email')
              ->addId('mailinglist-email')
              ->addPlaceholder('Your email')
              ->addCustom('p-holder ng-model="pf.formData.email" required')
              ->addValue('Your email')
              ->closeTag()
              ->createInput('image')
              ->addName('subscribe-btn')
              ->addId('subscribe-btn')
              ->addSrc($btn['src'])
              ->addAlt($btn['alt'])
              ->closeTag()
              ->endForm()
              ->get_form();
        return $form;
    }
    
    public function get_introduce_form(){
        $forms=new \Core\Htmlforms();
        $btn=$this->Kernel->Content->insert_asset('img', 'subscribeBtn');        
        $form=$forms->startForm('subscription-introduce')
              ->addNgController("processSubscriptionIntroduceFormCtrl as processSubscription")
              ->addNgSubmit("processSubscription.submit()")
              ->closeTag()
                
              ->createInput('text')
              ->addCustom('required')
              ->addName('fName')
              ->addNgModel("processSubscription.formData.fName")
              ->addId('mailinglist-name')
              ->addPlaceholder('Your name')
              ->addRequired(TRUE)
              ->closeTag()
                
              ->createInput('text')
              ->addName('lName')
              ->addRequired(TRUE)
              ->addId('mailinglist-surname')
              ->addNgModel("processSubscription.formData.lName")
              ->addPlaceholder('Your surname')
              ->closeTag()
                
              ->createInput('hidden')
              ->addName('euid')
              ->addId('mailinglist-id')
              ->addNgModel("processSubscription.formData.euid")
              ->closeTag()
                
              ->createInput('image')
              ->addName('subscribe-btn')
              ->addId('subscribe-btn')
              ->addSrc($btn['src'])
              ->addAlt($btn['alt'])
              ->closeTag()
              ->endForm()
              ->get_form();
        return $form;
    }
    
}

?>
