<?php
namespace Core;
// This class should take a table in the DB and generate a form 
// based on the table structure
// will be used in the admin panel

class FormsFactory {
    private $schemaManager;
    private $metaPath="php/Bundle/Doctrine/Meta/";
    
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    private function setSchemaManager(){
        $Conn=$this->Kernel->entityManager->getConnection();
        $this->schemaManager=$Conn->getSchemaManager();
    }
    
    private function get_table_name($repository){
        $table = $this->Kernel->entityManager->getClassMetadata($repository)->getTableName();
        return $table;
    }


    private function get_table_info($table){ 
        $columns=$this->schemaManager->listTableColumns($table);
        return $columns;
    }
    
    public function generate_default_table($repository,$formName, $formAction=NULL, $itemData=NULL, $ngController=NULL){
        $this->setSchemaManager();
        $table=$this->get_table_name($repository);
        $columns=$this->get_table_info($table);
        
        
        $Forms=new Htmlforms();
        $Forms->startForm($formName, $formAction,'POST',TRUE)
                ->addCustom('class="admin--standard"');
                if($formAction==NULL && $ngController!=NULL){
                    $Forms->addNgSubmit($ngController.'.generatedFormSubmit()');
                }
                $Forms->closeTag();
        
        foreach ($columns as $column) {
                $label=ucfirst(str_replace('_', ' ', Utils::string_decode_camelCase($column->getName())));
                switch ($column->getName()){
                    case 'id':
                             if (isset($itemData)&&isset($itemData['id'])){
                                $Forms->createInput('hidden')
                                    ->addName($column->getName());
                                    if(isset($itemData)&& isset($itemData["id"])){
                                        $Forms->addValue($itemData["id"]);
                                    }
                                    $Forms->closeTag();
                            }
                        break;
                    case 'pic':
                            $Forms->label($label);
                            $Forms->createInput('file')
                                ->addName($column->getName())
                                ->closeTag();
                        break;
                    default : 
                            switch (strtolower((string)$column->getType())){
                                case "text":                            
                                        $Forms->label($label);
                                        $Forms->startTextarea()
                                        ->addName($column->getName())
                                        ->addCustom(' class="ck" ');
                                        if($formAction==NULL && $ngController!=NULL){
                                            $Forms->addNgModel($ngController.'.generatedFormData.'.$column->getName());
                                            $Forms->addCustom(' ck-editor ');
                                            
                                        }
                                        $Forms->closeTag();                            
                                        if(isset($itemData)&& isset($itemData[$column->getName()])){
                                            $Forms->addCustom($itemData[$column->getName()]);
                                        }
                                        $Forms->endTextarea();
                                    break;
                                 case "string"||"integer"||"float":
                                        $Forms->label($label);
                                        $Forms->createInput('text')
                                        ->addName($column->getName());
                                        if($formAction==NULL && $ngController!=NULL){
                                            $Forms->addNgModel($ngController.'.generatedFormData.'.$column->getName());
                                        }
                                        if(isset($itemData)&& isset($itemData[$column->getName()])){
                                            $Forms->addValue($itemData[$column->getName()]);
                                        }
                                        $Forms->addPlaceholder($label)
                                        ->closeTag();
                                    break;
                            }
                        break;               
            }
        }
        $Forms->createInput('submit')
            ->addId('submit')
            ->addValue('submit')
            ->addName('submit');            
            $Forms->closeTag();
        $form=$Forms->endForm()
                ->get_form();
        return $form;        
    }
    
    public function generate_form($repository,$formName, $formAction=NULL, $itemData=NULL, $replacementArray=array()){
        $this->setSchemaManager();
        $table=$this->get_table_name($repository);
        $columns=$this->get_table_info($table);
        
        $meta=Utils::read_json($this->metaPath.$table.'.json');
        
        $Forms=new Htmlforms();
        $this->start_form($Forms, $formName, $formAction);
        
        foreach ($meta as $field=>$type) {
            $label=ucfirst(str_replace('_', ' ', Utils::string_decode_camelCase($field)));            
            switch ($type){
                case 'hidden':                            
                        $this->create_input_hidden($Forms,$field, $itemData);
                    break;
                case 'file':
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_file($Forms,$field, $label);
                        $Forms->addCustom('</div>');
                    break;
                case 'textarea':
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_textarea($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break;
                 case "text":
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_text($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break;
                case "number":
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_number($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break; 
                case "float":
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_float($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break;
                case "email":
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_email($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break; 
                case "checkbox":
                        $Forms->addCustom('<div class="ffield">');
                            $this->create_input_checkbox($Forms, $field, $label, $itemData);
                        $Forms->addCustom('</div>');
                    break; 
            }           
        }
        $this->create_submit($Forms);
        $form=$Forms->endForm()
                ->get_form();
        
        #replace custom strings in the form
        if(!empty($replacementArray)){
            $form=Parsers::simple_replacement($form, $replacementArray);
        }
        
        return $form;       
    }
    
    private function start_form(Htmlforms $Forms,$formName,$formAction){
        $Forms->startForm($formName, $formAction,'POST',TRUE)
                ->addCustom('class="admin--standard"')
                ->closeTag();
    }
    
    private function create_input_hidden(Htmlforms $Forms,$field,$itemData=NULL){
        if (isset($itemData)&&isset($itemData[$field])){
            $Forms->createInput('hidden')
                ->addName($field);
                if(isset($itemData)&& isset($itemData[$field])){
                    $Forms->addValue($itemData[$field]);
                }
                $Forms->closeTag();
        }
    }
    
    private function create_input_file(Htmlforms $Forms,$field,$label){
        $Forms->label($label);
        $Forms->createInput('file')
            ->addName($field)
            ->closeTag();
    }
    
    private function create_input_text(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->createInput('text')
        ->addName($field);
        if(isset($itemData)&& isset($itemData[$field])){
            $Forms->addValue($itemData[$field]);
        }
        $Forms->addPlaceholder($label)
        ->closeTag();
    }
    
    private function create_textarea(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->startTextarea()
        ->addName($field)
        ->addCustom('class="ck"')                            
        ->closeTag();                            
        if(isset($itemData)&& isset($itemData[$field])){
            $Forms->addCustom($itemData[$field]);
        }
        $Forms->endTextarea();
    }
    
    private function create_input_email(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->createInput('email')
        ->addName($field);
        if(isset($itemData)&& isset($itemData[$field])){
            $Forms->addValue($itemData[$field]);
        }
        $Forms->addPlaceholder($label)
        ->closeTag();
    }
    
    private function create_input_number(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->createInput('number')
        ->addName($field);
        if(isset($itemData)&& isset($itemData[$field])){
            $Forms->addValue($itemData[$field]);
        }
        $Forms->closeTag();
    }
    
    private function create_input_float(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->createInput('number')
                ->addStep('any')
        ->addName($field);
        if(isset($itemData)&& isset($itemData[$field])){
            $Forms->addValue($itemData[$field]);
        }
        $Forms->addPlaceholder($label)
        ->closeTag();
    }
    
    # default checkbox for TRUE/FALSE values only
    private function create_input_checkbox(Htmlforms $Forms,$field,$label,$itemData=NULL){
        $Forms->label($label);
        $Forms->createInput('checkbox')
        ->addName($field)
        ->addValue(1);
        if(isset($itemData)&& isset($itemData[$field])&&$itemData[$field]==1){
            $Forms->addChecked();
        }
        $Forms->addPlaceholder($label)
        ->closeTag();
    }
    
    private function create_submit(Htmlforms $Forms){
        $Forms->createInput('submit')
            ->addId('submit')
            ->addValue('submit')
            ->addName('submit')
            ->closeTag();
    }
    
}

?>
