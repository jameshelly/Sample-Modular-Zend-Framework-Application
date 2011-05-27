<?php

require_once realpath(__DIR__ . '/../library') . '/Doctrine/Common/ClassLoader.php';
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/../library'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/../library'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common',  realpath(__DIR__ . '/../library'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony',  realpath(__DIR__ . '/../library/Doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', realpath(__DIR__ . '/../application/models/entities'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(__DIR__ . '/../application/models/proxies'));
$classLoader->register();

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library/Wednesday'),
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH),
    '/Users/mrhelly/Documents/Server/Libraries/',
    get_include_path() . '/Wednesday',
    get_include_path() . '/Doctrine',
    get_include_path() . '/ZFDebug',
    get_include_path() . '/ZendX',
    get_include_path() . '/Zend',
    get_include_path()
)));

// get Zend Ini
require_once 'Zend/Config/Ini.php';
$zfconfig = new Zend_Config_Ini( APPLICATION_PATH . '/configs/application.ini', 'development');

$connectionOptions = array(
    'driver'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->driver,
    'host'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->host,
    'port'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->port,
    'dbname'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->dbname,
    'user'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->user,
    'password'		=> $zfconfig->resources->doctrine->dbal->connections->default->parameters->password
//    'unix_socket'	=> $zfconfig->resources->doctrine->connection->unix_socket
);

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$entityPath = $zfconfig->resources->doctrine->orm->entityManagers->default->metadataDrivers;
foreach($entityPath as $key => $entPath){
   //echo $key." - ".print_r($entPath->mappingDirs,true)."\n";
   foreach ($entPath->mappingDirs as $mapping) {
       $mapDir = $mapping;
   }
}
//die($mapDir);//print_r($entityPath,true));
$driverImpl = $config->newDefaultAnnotationDriver(array($mapDir));
//$driverImpl = new \Doctrine\ORM\Mapping\Driver\XmlDriver(realpath(__DIR__ . '/../application/models/config'));

$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir($zfconfig->resources->doctrine->orm->entityManagers->default->proxy->dir);
$config->setProxyNamespace($zfconfig->resources->doctrine->orm->entityManagers->default->proxy->dir);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);