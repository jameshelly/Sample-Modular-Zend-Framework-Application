<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Define path to core directory
defined('CORE_PATH')
    || define('CORE_PATH', realpath(dirname(__FILE__) . '/../..'));

// Define application environment
defined('BASE_URL')
    || define('BASE_URL', 'current');

// Define application server string
defined('APPLICATION_SRV')
	|| define('APPLICATION_SRV', str_replace('.', '_', BASE_URL));

// Define main configuration ini
defined('CONFIG_PATH')
	|| define('CONFIG_PATH', APPLICATION_PATH . '/configs/default.ini');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/Doctrine'),
    get_include_path(),
)));

require_once CORE_PATH . '/private/library/ZendX/Loader/AutoloaderFactory.php';
ZendX_Loader_AutoloaderFactory::factory(array(
    'ZendX_Loader_ClassMapAutoloader' => array(
        APPLICATION_PATH . '/.classmap.php',
        CORE_PATH . '/private/library/.classmap.php',
    ),
    'ZendX_Loader_StandardAutoloader' => array(
        'prefixes' => array(
            'Zend' => CORE_PATH . '/private/library/Zend',
            'ZendX' => CORE_PATH . '/private/library/ZendX',
        ),
        'namespaces' => array(
            'Application' => APPLICATION_PATH,
            'Wednesday' => CORE_PATH . '/private/library/Wednesday',
            'Doctrine' => CORE_PATH . '/private/library/Doctrine',
            'Gedmo' => CORE_PATH . '/private/library/Gedmo',
            'Zend' => CORE_PATH . '/private/library/Zend',
            'ZendX' => CORE_PATH . '/private/library/ZendX',
        ),
        'fallback_autoloader' => true,
    ),
));

/** Zend_Application */
//require_once 'Zend/Application.php';

// Creating application
$application = new Zend_Application(
    APPLICATION_SRV,
    APPLICATION_PATH . '/configs/default.ini'
);

date_default_timezone_set('Europe/London');

// Bootstrapping resources
$bootstrap = $application->bootstrap()->getBootstrap();
$bootstrap->bootstrap();
$container = $application->getBootstrap()->getResource('doctrine');

// Console
$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\Common\Version::VERSION
);

try {
    // Bootstrapping Console HelperSet
    $helperSet = array();

    if (($dbal = $container->getConnection(getenv('CONN') ?: @$container->defaultConnection)) !== null) {
        $helperSet['db'] = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($dbal);
    }

    if (($em = $container->getEntityManager(getenv('EM') ?: @$container->defaultEntityManager)) !== null) {
        $helperSet['em'] = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    }
} catch (\Exception $e) {
    $cli->renderException($e, new \Symfony\Component\Console\Output\ConsoleOutput());
}

$cli->setCatchExceptions(true);
$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet($helperSet));

$cli->addCommands(array(
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),

));

$cli->run();
