<?php
// doctrine_bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

# Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
//$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
$config = Setup::createAnnotationMetadataConfiguration(array($_SERVER['DOCUMENT_ROOT']."/php/Bundle/Doctrine/Entities"), $isDevMode);

$conn=Core\Utils::read_json(dirname(__FILE__).'/db.json');

# obtaining the entity manager
$entityManager = EntityManager::create($conn['mysql'], $config);



