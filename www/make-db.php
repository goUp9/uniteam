<?php
session_start();
require_once dirname(__FILE__).'/php/Core/'.'Autoload.php';
Core\Autoload::register_autoload();
Dev\Debug::set_errors_on();
Dev\Debug::development_mode(TRUE);

require dirname(__FILE__).'/config/doctrine_bootstrap.php';


$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$classes = $entityManager->getMetadataFactory()->getAllMetadata();
//$schemaTool->dropSchema($classes);
//$schemaTool->createSchema($classes);
$schemaTool->updateSchema($classes);


