<?php
/*
 * Zf11D2 FrontEnd
 * Index.php
 * James A Helly. 28-05-2011.
 */

// Define path to core directory
defined('CORE_PATH')
    || define('CORE_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to web directory
defined('WEB_PATH')
    || define('WEB_PATH', realpath(dirname(__FILE__) . '/'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(CORE_PATH . '/private/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', getenv('APPLICATION_ENV'));

// Define application environment
defined('BASE_URL')
    || define('BASE_URL', $_SERVER['SERVER_NAME']);

// Define application server string
defined('APPLICATION_SRV')
	|| define('APPLICATION_SRV', str_replace('.', '_', BASE_URL));

// Ensure library/ is on include_path
//*
set_include_path(implode(PATH_SEPARATOR, array(
	realpath('/Users/mrhelly/Documents/Server/Libraries'),
	realpath(APPLICATION_PATH . '/../library'),
/*
	realpath(APPLICATION_PATH . '/../library/Doctrine'),
	realpath(APPLICATION_PATH . '/../library/Opentag'),
	realpath(APPLICATION_PATH . '/../library/Gendmo'),
	realpath(APPLICATION_PATH . '/../library/Opentag/Application/Resource'),
//*/
	realpath(APPLICATION_PATH),
	get_include_path()
)));
//*/
require_once CORE_PATH . '/private/library/ZendX/Loader/AutoloaderFactory.php';
ZendX_Loader_AutoloaderFactory::factory(array(
    'ZendX_Loader_ClassMapAutoloader' => array(
        CORE_PATH . '/private/library/.classmap.php',
        CORE_PATH . '/private/application/.classmap.php',
    ),
    'ZendX_Loader_StandardAutoloader' => array(
        'prefixes' => array(
            'Application' => CORE_PATH . '/private/application',
            'Opentag' => CORE_PATH . '/private/library/Opentag',
        ),
        'fallback_autoloader' => true,
    ),
));

if(APPLICATION_ENV == 'development') {
	ini_set("error_reporting", E_ALL ^ E_DEPRECATED);
}

/** Zend_Application */
//require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application( APPLICATION_SRV, APPLICATION_PATH . '/configs/default.ini' );
$application->bootstrap()->run();