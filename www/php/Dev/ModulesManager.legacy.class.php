<?php
namespace Dev;
/**
 * Description of ModulesManager
 * @package:
 * @author: Anastasia Sitnina
 * @version:
 */
class ModulesManager {
    public $pathDistModules='/../../dist/php/Modules/';
    public $pathBuildModules='/../../php/Modules/';
    
    public $pathDistController='/../../dist/php/Bundle/Controllers/';
    public $pathBuildController='/../../php/Bundle/Controllers/';
    
    public $pathDistCommonAssets='/../../dist/php/Bundle/Controllers/Assets/';
    public $pathBuildCommonAssets='/../../php/Bundle/Controllers/Assets/';
    
    public $pathDistConfig='/../../dist/config/';
    public $pathBuildConfig='/../../config/';
    
    public $pathDistCss='/../../dist/css/';
    public $pathBuildCss='/../../css/';
    
    public $pathDistJs='/../../dist/js/App/';
    public $pathBuildJs='/../../js/App/';
    
    public $pathDistImg='/../../dist/img/';
    public $pathBuildImg='/../../img/';
    
    public $pathDistEntites='/../../dist/php/Bundle/Doctrine/Entities/';
    public $pathBuildEntites='/../../php/Bundle/Doctrine/Entities/';
    
    public $pathDistTemplates='/../../dist/templates/';
    public $pathBuildTemplates='/../../templates/';
    
    public $pathBuildDbTables='';
    
    public function __construct(\Doctrine\ORM\EntityManager $entityManager){ 
        $this->entityManager=$entityManager;
    }
    
    function get_all_modules(){
        $mods=scandir(dirname(__FILE__).$this->pathDistModules);
        $abouts=array();        
        foreach($mods as $mod){
            if($mod!=='.' && $mod!=='..'){                
                array_push($abouts,\Core\Utils::read_json(dirname(__FILE__).$this->pathDistModules.$mod.'/about.json'));
            }
        }
        return $abouts;
    }
    
    function get_folderName_from_title($mod_title){
        return $folderName=  ucfirst(str_replace('_','',str_replace(' ','',$mod_title)));        
    }
    
    function add_module($folder_name){ 
        #get about info
        $about=\Core\Utils::read_json(dirname(__FILE__).$this->pathDistModules.$folder_name.'/about.json');
        
        #add mod folder to Modules
        $this->add_mod_folder_to_modules($folder_name);
                
        #if controlles exists - add controller
        if(isset($about['controller'])&&!empty($about['controller'])){            
            $this->add_mod_folder_to_controllers($about['controller']);
        }
        
        #if config exists - add config
        if(isset($about['config'])&&!empty($about['config'])){            
            $this->add_mod_folder_to_config($about['config']);
        }        
        
        #if templates exist - add templates
        if(isset($about['templates'])&& !empty($about['templates'])){
            $this->add_mod_folder_to_templates($about['templates']);
        }
        
        #if doctrine entities exist - add doctrine entities
        if(isset($about['entities'])&& !empty($about['entities'][0])){
            $this->add_mod_to_entities($about['entities']);
            if(isset($_SESSION['installation']['mysql']) && !empty($_SESSION['installation']['mysql']['dbname'])){
                #create the db table
                if(isset($about['mysql table'])&&!empty($about['mysql table'])){
                    foreach($about['mysql table'] as $table_file){
                        $DbManager=new DbManager($this->entityManager);
                        $DbManager->create_db_table_from_dist($table_file);
                    }
                }
            }
        }
        
        #if img exist - add images
        if(isset($about['img'])&&!empty($about['img'])){
            $this->add_mod_folder_to_img($about['img']);
        }
        
        #if common assets exist - add doctrine entities
        if(isset($about['common assets'])&& !empty($about['common assets'])){
            $this->add_mod_to_commonAssets($about['common assets']);            
        }
        
        #if common assets exist - add doctrine entities
        if(isset($about['js'])&& !empty($about['js'])){
            $this->add_mod_folder_to_js($about['js']);            
        }
        
         #if common assets exist - add doctrine entities
        if(isset($about['css'])&& !empty($about['css'])){
            $this->add_mod_folder_to_css($about['css']);            
        }
        
        return true;
    }
    
    private function add_mod_folder_to_modules($module_folder){
            try{
                if(!is_dir(dirname(__FILE__).$this->pathBuildModules.$module_folder.'/')){
                    mkdir(dirname(__FILE__).$this->pathBuildModules.$module_folder.'/');
                }
                else {
                    throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildModules.$module_folder.'/'.' already exists!'); 
                }
            }
            catch(Exceptions\LogicalException $E){
                $E->directory_exists(dirname(__FILE__).$this->pathBuildModules.$module_folder.'/');
            }
            \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistModules.$module_folder.'/', dirname(__FILE__).$this->pathBuildModules.$module_folder.'/');
            return true;        
    }
    
    private function add_mod_folder_to_controllers($controller){
        try{
            if(!is_dir(dirname(__FILE__).$this->pathBuildController.$controller.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildController.$controller.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildController.$controller.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildController.$controller.'/');
        }        
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistController.$controller.'/', dirname(__FILE__).$this->pathBuildController.$controller.'/');
        return true;
    }
    
    private function add_mod_folder_to_templates($templateFolder){
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildTemplates.$templateFolder.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildTemplates.$templateFolder.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildTemplates.$templateFolder.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildTemplates.$templateFolder.'/');
        }        
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistTemplates.$templateFolder.'/', dirname(__FILE__).$this->pathBuildTemplates.$templateFolder.'/');
    }
    
    private function add_mod_folder_to_css($cssFolder){
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildCss.$cssFolder.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildCss.$cssFolder.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildCss.$cssFolder.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildCss.$cssFolder.'/');
        }         
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistCss.$cssFolder.'/', dirname(__FILE__).$this->pathBuildCss.$cssFolder.'/');
    }
    
    private function add_mod_folder_to_js($jsFolder){
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistJs.$jsFolder.'/', dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/');
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Controllers/')){
                mkdir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Controllers/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Controllers/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Controllers/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistJs.$jsFolder.'/Controllers/', dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Controllers/');
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Directives/')){
                mkdir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Directives/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Directives/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Directives/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistJs.$jsFolder.'/Directives/', dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Directives/');
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Services/')){
                mkdir(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Services/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Services/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Services/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistJs.$jsFolder.'/Services/', dirname(__FILE__).$this->pathBuildJs.$jsFolder.'/Services/');
    }
    
    private function add_mod_folder_to_img($imgFolder){
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistImg.$imgFolder.'/', dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/');
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/assets/')){
                mkdir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/assets/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/assets/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/assets/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistImg.$imgFolder.'/assets/', dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/assets/');
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/css/')){
                mkdir(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/css/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/css/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/css/');
        }
        \Core\Files::copy_all_files(dirname(__FILE__).$this->pathDistImg.$imgFolder.'/', dirname(__FILE__).$this->pathBuildImg.$imgFolder.'/css/');        
    }
    
    private function add_mod_to_entities($entities){
        foreach($entities as $entity){
            copy(dirname(__FILE__).$this->pathDistEntites.'/'.$entity,dirname(__FILE__).$this->pathBuildEntites.'/'.$entity);
        }
    }
    
    private function add_mod_to_commonAssets($assets){
        foreach($assets as $asset){
            copy(dirname(__FILE__).$this->pathDistCommonAssets.'/'.$asset,dirname(__FILE__).$this->pathBuildCommonAssets.'/'.$asset);
        }
    }
    
    private function add_mod_folder_to_config($config){
        try{
             if(!is_dir(dirname(__FILE__).$this->pathBuildConfig.$config.'/')){
                mkdir(dirname(__FILE__).$this->pathBuildConfig.$config.'/');
            }
            else {
                throw new Exceptions\LogicalException('Directory '.dirname(__FILE__).$this->pathBuildConfig.$config.'/'.' already exists!'); 
            }
        } catch (Exceptions\LogicalException $E) {
            $E->directory_exists(dirname(__FILE__).$this->pathBuildConfig.$config.'/');
        }
        
        copy(dirname(__FILE__).$this->pathDistConfig.$config.'/assets.json', dirname(__FILE__).$this->pathBuildConfig.$config.'/assets.json');
        
        #add module maps to other maps
        $mod_maps=\Core\Utils::read_json(dirname(__FILE__).$this->pathDistConfig.$config.'/maps.json');
        $maps=\Core\Utils::read_json(dirname(__FILE__).$this->pathBuildConfig.'/maps.json');
        $maps['routes']=array_merge($maps['routes'],$mod_maps['routes']);
        $maps=json_encode($maps,JSON_PRETTY_PRINT);
        $handle=fopen(dirname(__FILE__).$this->pathBuildConfig.'/maps.json', 'w');
        fwrite($handle, $maps);
    }
    
    
    
}

?>
