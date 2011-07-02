<?php
define('LIB_PATH', realpath('/Users/mrhelly/Documents/Server/Libraries'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('/Users/mrhelly/Documents/Server/Libraries'),
//	realpath(APPLICATION_PATH . '/../library/Opentag'),
//	realpath(APPLICATION_PATH . '/../library/Doctrine'),
//	realpath(APPLICATION_PATH . '/../library/DoctrineExtensions'),
//	realpath(APPLICATION_PATH . '/../library/Zend'),
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH),
    get_include_path()
)));

require_once 'Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Gedmo',  LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony',  realpath(LIB_PATH . '/Doctrine'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

$entityPathes = array();
$dir_handle = opendir(APPLICATION_PATH."/modules");
while ($file = readdir($dir_handle)) {
    if($file == "." || $file == ".." || $file == ".DS_Store") {
        continue;
    } else /* if (is_dir($file)) */ {
        $entityPathes[] = APPLICATION_PATH."/modules/".$file."/entities";
        //$entityPathes[] = APPLICATION_PATH."/../library/DoctrineExtensions/WedVersionable/Entity";
    }
}
closedir($dir_handle);

$driverImpl = $config->newDefaultAnnotationDriver($entityPathes);
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(realpath(APPLICATION_PATH."/../data/generated"));
$config->setProxyNamespace('Proxies');

// get Zend Ini
require_once 'Zend/Config/Ini.php';
//TODO Work out a way of getting the server name without having to hard code it.
$zfconfig = new Zend_Config_Ini( APPLICATION_PATH . '/configs/default.ini', 'jah_zfdoc2_local');

/*
$zfconfig->resources->doctrine->dbal->default
$zfconfig->resources->doctrine->orm->manager->connection
*/

$connectionOptions = array(
    'driver'		=> $zfconfig->resources->doctrine->dbal->default->driver,
    'host'			=> $zfconfig->resources->doctrine->dbal->default->host,
    'port'			=> $zfconfig->resources->doctrine->dbal->default->port,
    'dbname'		=> $zfconfig->resources->doctrine->dbal->default->dbname,
    'user'			=> $zfconfig->resources->doctrine->dbal->default->user,
    'password'		=> $zfconfig->resources->doctrine->dbal->default->password,
    'unix_socket'	=> $zfconfig->resources->doctrine->dbal->default->unix_socket
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);
