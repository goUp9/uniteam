<?php
namespace Modules;
abstract class Form implements Forms{
    protected $formName;
    protected $formAction;
    
    public $form;
            
    function set_formName($formName) {
        $this->formName=$formName;
    }
    
    function set_formAction($formAction=CURRENT_PAGE) {
        $this->formAction=$formAction;
        
    }
    
    public function __construct(\Core\Kernel $Kernel, $formName="form__generic",$formAction=CURRENT_PAGE,$args=array()) {
        $this->Kernel=$Kernel;
        $this->formName=$formName;
        $this->formAction=$formAction;
        $this->args=$args;
        
        $this->form=  $this->html_form($args);
    }
    
    /* 
     *  @param $args - array of arguments to work with inside the form (e.g. data to be set for fields)
     *  @return - html form 
     */
    abstract public function html_form($args);
}
