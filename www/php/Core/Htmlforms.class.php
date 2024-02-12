<?php
namespace Core;
/**
 * Class creates HTML forms and elements of HTML forms.
 * @package: core
 * @author: Anastasia Sitnina
 * @version: 3.0 
 */
class Htmlforms {
    protected $formName;
    protected $formAction;
    
    #fields names
    protected $fields;
    
    function getFields(){
        $fields= $this->fields;        
        return $fields;
    }
    
    #values to return
    #finished form
    protected $form;
    
    function debug(){
        echo '<pre>';
        print_r($this->form);
        echo '</pre>';
        return $this;
    }
    
    #final function to get created form and refresh the cache
    function get_form(){
        $form=$this->form; 
        $this->set_form("");
        return $form;
    }
    # changes value of the cached form
    function set_form($form){
        $this->form=$form;
        return $this;
    }
    
    function add_to_form($form){
        $this->form.=$form;
        return $this;
    }
    
    
    function closeTag(){
        $this->form.=" >";
        return $this;
    }
    
    
    #starting a new form;
    #by default $method=POST;
    #file uploading disabled by default ;     
    function startForm ($name, $action="", $method='POST', $uploadfile=false){        
        $contentsForm= "<form ";
        $contentsForm=$contentsForm.'name="'.$name.'" '; 
        $contentsForm=$contentsForm.'id="'.$name.'" ';
        if($action!=""){
            $contentsForm=$contentsForm.'action="'.$action.'" ';  
        }
        $contentsForm=$contentsForm.'method="'.$method.'" ';
        if ($uploadfile){
            $contentsForm=$contentsForm.'enctype="multipart/form-data" ';            
        }
        //$contentsForm=$contentsForm.'>
        //    ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    #closing the form
    function endForm (){
        $contentsForm='</form>';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function label ($label, $for=NULL){
        $contentsForm='
            <label ';
        if (isset($for)){
            $contentsForm=$contentsForm.'for="'.$for.'"';
        }
        $contentsForm=$contentsForm.' >';
        $contentsForm=$contentsForm.$label;
        $contentsForm=$contentsForm.'</label>';
        $this->form.=$contentsForm;
        return $this;
    }
    
    
    
    
    /*
     *  Functions that create form tags
     */
    
    
    
    
    function createInput($type){
        $contentsForm=' <input type="'.$type.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
     function startSelect(){
        $contentsForm=' <select ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function endSelect(){
        $contentsForm='</select>';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function startFieldSet(){
        $contentsForm=' <fieldset ';        
        $this->form.=$contentsForm;
        return $this;
    }
    
    function endFieldSet(){
        $contentsForm=' </fieldset>';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function startOption (){
        $contentsForm='     <option ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function endOption(){
        $contentsForm='     </option>';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function startTextarea(){      
        $contentsForm='<textarea ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function endTextarea(){
        $contentsForm='</textarea>';
        $this->form.=$contentsForm;
        return $this;
    }

    #add any custom data to a form
    function addCustom($content){
        $this->add_to_form($content);
        return $this; 
    }
    
    
    
    
    /*
     * Functions that add attributes to form tags
     */
    
    
    
    
    function addName($name){
        $contentsForm=' name="'.$name.'" ';
         $this->form.=$contentsForm;
        return $this;
    }
        
    function addValue($value){
        $contentsForm=' value="'.$value.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addMaxlength($maxlength){
        $contentsForm=' maxlength="'.$maxlength.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addPattern($pattern){
        $contentsForm=' pattern="'.$pattern.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addTitle($title){
        $contentsForm=' title="'.$title.'" ';
        $this->form.=$contentsForm;
        return $this;
    }    
    
    
    function addStep($step){
        $contentsForm=' step="'.$step.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addReadonly($readonly=true){
        if ($readonly){
            $contentsForm=' readonly="readonly" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addPlaceholder($placeholder){
        if ($placeholder){
            $contentsForm=' placeholder="'.$placeholder.'" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addDisabled($disabled=true){
        if ($disabled){
            $contentsForm=' disabled="disabled" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addId($id){
        $contentsForm=' id="'.$id.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addClass($class){
        $contentsForm=' class="'.$class.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addChecked($checked=true){
        if ($checked){
            $contentsForm=' checked="checked" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addSelected(){
        $contentsForm=' selected="selected" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addSrc($src){
        $contentsForm=' src="'.$src.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addAlt($alt){
        $contentsForm=' alt="'.$alt.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addMultiple($multiple=true){
         if ($multiple){
            $contentsForm=' multiple="multiple" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addRequired($required=true){
         if ($required){
            $contentsForm=' required="required" ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addAutofocus($autofocus=true){
        if ($autofocus){
            $contentsForm=' autofocus ';
        }
        $this->form.=$contentsForm;
        return $this;
    }
    
    
    /* AngularJS specific attributes */
    
    function addNgModel($ngModel){
        $contentsForm=' data-ng-model="'.$ngModel.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgClick($ngClick){
        $contentsForm=' data-ng-click="'.$ngClick.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgChange($ngChange){
        $contentsForm=' data-ng-change="'.$ngChange.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgFocus($ngFocus){
        $contentsForm=' data-ng-focus="'.$ngFocus.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgController($ngController){
        $contentsForm=' data-ng-controller="'.$ngController.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgRepeat($ngRepeatValue, $asValue){
        $contentsForm=' data-ng-repeat="'.$asValue.' in '.$ngRepeatValue.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgSubmit($ngSubmit){
        $contentsForm=' data-ng-submit="'.$ngSubmit.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    function addNgInit($ngInit){
        $contentsForm=' data-ng-init="'.$ngInit.'" ';
        $this->form.=$contentsForm;
        return $this;
    }
    
    
    

  
}

?>
