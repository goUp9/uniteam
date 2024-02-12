<?php
namespace Controllers\Ajax;
class Templates {
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    public function get_template(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('feedback_text'));
        
//        $Subscription=new \Modules\Subscription\Forms($this->Kernel);        
//       
//        $this->Kernel->Content->set_form($Subscription->get_subscription_form(), 'subscription');
//        $this->Kernel->Content->set_form($Subscription->get_introduce_form(), 'subscription_introduce');
        
        $this->Kernel->Response->template=$this->Kernel->Request->post['template'];
        $this->Kernel->Response->pathToTemplate='/templates/website/widgets/';

        return $this->Kernel;
    }
    
}

?>
