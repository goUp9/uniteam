<?php
namespace Bundle\Controllers\Where;
class EditForm extends \Modules\Form{  
    function html_form($args) {
//        $btn=$this->Kernel->Content->insert_asset('img','submit_btn');
        
       
        
        $this->Kernel->Forms->startForm($this->formName,  $this->formAction) 
                ->addNgSubmit("where.submit()");
                if($args){
                    $this->Kernel->Forms->addCustom('ng-init="where.formData.query_where_id='.$args[0]['wheres'][0]['id'].'"');
                }
                $this->Kernel->Forms->closeTag();
        
        $this->Kernel->Forms->startFieldSet()->closeTag();
        if($args){
            $this->Kernel->Forms->label('Change Location <b>(Current location is '.$args[0]['wheres'][0]['place']["formattedAddress"].')</b>');
        }
        else {
            $this->Kernel->Forms->label('Location');
        }
        $this->Kernel->Forms->createInput('text')
            ->addName('place')
            ->addCustom(' data-geo-helper ')
            ->addCustom(' location="geoData" ')
            ->addPlaceholder('Location or Postcode')
            ->closeTag();            
        $this->Kernel->Forms->endFieldSet();
        
        $this->Kernel->Forms->startFieldSet()->closeTag();
        if($args){
            $this->Kernel->Forms->label('Change Radius:');
        }
        else {
            $this->Kernel->Forms->label('Radius:');
        }
        $this->Kernel->Forms->createInput('number')
            ->addName('radius')
            ->addCustom(' pattern="[0-9]+([\.|,][0-9]+)?"  step="0.1"
            title="This should be a number with up to 1 decimal." ')
            ->addNgModel('where.formData.radius');
            if($args){
                $this->Kernel->Forms->addCustom('ng-init="where.formData.radius='.$args[0]['wheres'][0]['radius'].'"');
            }
            $this->Kernel->Forms->addPlaceholder('Radius in miles')
            ->closeTag();  
        $this->Kernel->Forms->endFieldSet();

        $this->Kernel->Forms->createInput('submit')
                ->addName('submit')
                ->addValue('Confirm')
                ->addId('btn_submit')
                ->closeTag();        
        
        return $this->Kernel->Forms->endForm()                
                ->get_form();        
    
    }
    
    
}
