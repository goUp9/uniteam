<?php
namespace Bundle\Controllers\Index;
class Index {
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;        
    }
    
    function main(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('feedback_btn','motto')); 
        
        $Form=new HomeForm($this->Kernel,'search','');
        $this->Kernel->Content->set_form($Form->form,'search');
        $this->Kernel->Content->set_data('home','route');
        return $this->Kernel;
    }
    
    function about(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('about_main','about_ask','about_supply','feedback_btn','motto'));  
        return $this->Kernel;
    }
    
    function terms(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('terms'));  
        return $this->Kernel;
    }
    
    function privacy(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('privacy'));  
        return $this->Kernel;
    }
    
    function updates(){
        $TextMngr=new \Bundle\Controllers\Admin\DefaultTextsManager($this->Kernel);
        $TextMngr->compile_texts(array('updates'));  
        return $this->Kernel;
    }
}
