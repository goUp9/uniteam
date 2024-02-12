<?php


/* 
 * Mapping 
 */

/* General */
if (isset($_SERVER['SERVER_NAME'])){
    define('LINKS_PRE','http://'.$_SERVER['SERVER_NAME'].'/');
}

if (isset($_SERVER['HTTP_HOST'])&&isset($_SERVER['REQUEST_URI'])){
//    print_r('CURRENT_PAGE','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    define('CURRENT_PAGE','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}

define('WEBSITE_NAMESPACE','Bundle\Controllers\\');
define('COMMONS_NAMESPACE', WEBSITE_NAMESPACE.'Commons\\');

/* System Paths */
define('SOURCE_ROOT_PATH','php\\');
define('MAPS_PATH','config/maps.json');
define('ASSETS_PATH','config/assets.json');
define('DEV_LOG_PATH','dev/logs/');
define('SMTP_PATH','config/SMTP.json');
define('CSS_PATH', 'css/');
define('JS_PATH','js/');

define('DEPLOYMENT_PATH','deployment/');

define ('TEMPLATES_PATH','templates/website');


/* DB */
define('DOCTRINE_ENTITIES_PATH','Bundle\Doctrine\Entities\\');

/*
 *  SYSTEM SETTINGS
 */
define('ROUTESTACK_SIZE',10);

