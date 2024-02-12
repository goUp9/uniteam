<?php
namespace Modules;
interface Forms {
    
    public function set_formName($formName);
    
    public function set_formAction($formAction=CURRENT_PAGE);
    
}
