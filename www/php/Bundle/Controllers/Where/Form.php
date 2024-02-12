<?php
namespace Bundle\Controllers\Where;
class Form extends \Modules\Form{
    use \Core\Mapping;
    
    function html_form($args) {
//        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');

        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("where.submit()")
                ->closeTag();
        
        $this->Kernel->Forms->addCustom('<div class="buttons-wrap">');
        $this->Kernel->Forms->startFieldSet()->addCustom(' data-ng-hide="where.locsExistFlag"')->closeTag();        
        $this->Kernel->Forms->createInput('button')
            ->addName('anywhere')
            ->addNgClick('where.switch_anywhere()')   
            ->addValue('Search everywhere')
            ->addClass('btn')
            ->closeTag();  
        $this->Kernel->Forms->endFieldSet();
        $this->Kernel->Forms->addCustom('<p>Or</p>');
        $this->Kernel->Forms->addCustom('</div>');
        
        $this->Kernel->Forms->startFieldSet()->closeTag();        
        $this->Kernel->Forms->label('Location:'); 
        $this->Kernel->Forms->addCustom('<div class="close-btn-wrap">');
        $this->Kernel->Forms->createInput('text')
            ->addName('place')
            ->addCustom(' data-geo-helper ')
            ->addCustom(' location="geoData" ')
            ->addPlaceholder('Location or Postcode')
            ->closeTag(); 
        $this->Kernel->Forms->addCustom('<div class="empty-btn" ng-click="where.clear_loc()"><p>X</p></div>');
        $this->Kernel->Forms->addCustom('</div>');
        $this->Kernel->Forms->endFieldSet();
        
        $this->Kernel->Forms->startFieldSet()->closeTag();
        $this->Kernel->Forms->label('Radius:');
        $this->Kernel->Forms->createInput('number')
            ->addName('radius')
            ->addCustom(' pattern="[0-9]+([\.|,][0-9]+)?"  step="0.1"
            title="This should be a number with up to 1 decimal." ')
            ->addNgModel('where.formData.radius')
            ->addPlaceholder('Radius in miles')
            ->closeTag();  
        $this->Kernel->Forms->endFieldSet(); 
        $this->Kernel->Forms->addCustom('<p ng-if="geoData.formatted_address">Current location: {{geoData.formatted_address}}</p>');
        
        $this->Kernel->Forms->addCustom('<div class="buttons-wrap">');
        $route=  $this->get_current_route_map();
        $this->Kernel->Forms->createInput('submit')
                ->addName('submit')
                ->addClass('btn')
                ->addCustom('ng-show="geoData.formatted_address"');
                if($route['request']==='supply/where'||$route['request']==='advise/where'){
                    $this->Kernel->Forms->addValue('Add');
                }
                else {
                    $this->Kernel->Forms->addValue('Confirm');
                }                
                $this->Kernel->Forms->addId('btn_submit')
                ->closeTag();
         $this->Kernel->Forms->addCustom('</div>');
        
        return $this->Kernel->Forms->endForm()                
                ->get_form();        
    
    }
}
