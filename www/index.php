<?php
session_start();
date_default_timezone_set("Europe/London");
require_once dirname(__FILE__).'/php/Core/'.'Autoload.php';
Core\Autoload::register_autoload();
Dev\Debug::set_errors_on();
Dev\Debug::development_mode(TRUE);


require dirname(__FILE__).'/config/doctrine_bootstrap.php';
$Router=new Core\Router($entityManager);

$Kernel=$Router->route();
//\Dev\Debug::dump($_SESSION);
if(is_object($Kernel)){
    $Kernel->publish_view();
}

