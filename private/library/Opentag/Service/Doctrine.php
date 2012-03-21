<?php

namespace Opentag\Service;

/*
 * Opentag\Service\Doctrine
 *
 * Doctrine
 *
 *
 */

use Doctrine\DBAL\DriverManager,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\Common\EventManager,
    Doctrine\ORM\EntityManager,
    Doctrine\OXM\XmlEntityManager,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ORM\Configuration as ORMConfiguration,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver as ORMAnnotationDriver,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver as ODMAnnotationDriver,
    Doctrine\OXM\Mapping\Driver\AnnotationDriver as OXMAnnotationDriver,
    Doctrine\ORM\Mapping\Driver\DriverChain as DoctrineDriverChain,
    Doctrine\Common\Annotations\CachedReader as DoctrineCachedReader,
    Doctrine\DBAL\Logging\DebugStack as DBALDebugStack,
    Opentag\Auth\Adapter\Doctrine as AuthAdapterDoctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugDoctrine,
    \Zend_Application_Bootstrap_Bootstrapper as ZendApplicationBootstrapper,
    \Zend_Cache_Manager as CacheManager,
    \Zend_Controller_Front as Front,
    \Zend_Session as Session;

/**
 * Description of Doctrine
 *
 * $doctrine->evtm;
 * $doctrine->em;
 * $doctrine->conn;
 * $doctrine->log;
 * $doctrine->cfg;
 * $doctrine->getConnection();
 * $doctrine->getEventManager();
 * $doctrine->getEntityManager();
 *
 * @author mrhelly
 */
class Doctrine {

    /**
     * Parent bootstrap
     *
     * @var Zend_Application_Bootstrap_Bootstrapper
     */
    protected $_bootstrap;

    /**
     * Options for the resource
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Option keys to skip when calling setOptions()
     *
     * @var array
     */
    protected $_skipOptions = array('options', 'config');

    /**
     * @var  \Opentag\Service\Doctrine
     */
    protected $d2;

    /**
     * @var \Zend_Cache_Manager as CacheManager
     */
    protected $context;

    /**
     * @var \Zend_Cache_Manager as CacheManager
     */
    protected $cache;

    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * @var \Doctrine\Common\EventManager
     */
    protected $evtm;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entm;

    /**
     * @var \Doctrine\OXM\XmlEntityManager
     */
    protected $xmltm;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $docm;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $pdom;

    /**
     * @var Zend_Log
     */
    private $log;

    /**
     * @var \Doctrine\ORM\Configuration
     */
    private $cfg;

    /**
     * Constructor.
     *
     * @param array $config Doctrine Container configuration
     */
    public function __construct($options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        } else if ($options instanceof Zend_Config) {
            $this->setOptions($options->toArray());
        }
        $this->cfg = $this->getConfig();
    }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function init() {
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log');
        }
        $this->log->info(get_class($this) . '::init');
        $CacheOptions = $this->getOptions();
        $ConnectionOptions = false; //array();
        $EventOptions = false; //array();
        $EntityOptions = false; //array();
        $XmlEntityOptions = false; //array();
        $DocumentOptions = false; //array();

        $this->context = $this->getContext();
        $this->cache = $this->getCacheManager($CacheOptions);
        $this->conn = $this->getConnection($ConnectionOptions);
        $this->evtm = $this->getEventManager($EventOptions);
        $this->entm = $this->getEntityManager($EntityOptions);
//        $this->xmltm        = $this->getXmlEntityManager($XmlEntityOptions);
//        $this->doctm        = $this->getDocumentManager($DocumentOptions);
        $this->log->info(get_class($this) . '::init');
        return $this;
    }

    /**
     *
     * @param type $name
     * @return type
     */
    public function getConnection($name = 'default') {
        #Get logger
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log'); //$this->getBootstrap()->getResource('Log');//->getContainer()->get('logger');
        }
        $options = $this->getOptions();
//        $name = (($name != $options['orm']['manager']['connection'])&&($name == 'default'))?$options['orm']['manager']['connection']:$name;
        $this->log->debug(get_class($this) . '::getConnection(' . $name . ')');
        if (($name != 'default') && ($name != '')) {
            return $this->_buildConnection($name);
        }
        if ((null === $this->conn)) {
            $this->conn = $this->_buildConnection($name);
        }
        $this->getBootstrap()->getContainer()->set('doctrine.connection', $this->conn);
        return $this->conn;
    }

    /**
     * Init Event Manager
     *
     * @param type $options
     * @return type
     */
    public function getEventManager($options = false) {
        #Get logger
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $options = ($options == false) ? $this->getOptions() : $options;
        $this->log->info(get_class($this) . '::getEventManager');
        if ((null === $this->evtm)) {
            $this->evtm = $this->_buildEventManager($options);
        }
        $this->getBootstrap()->getContainer()->set('event.manager', $this->entm);
        return $this->evtm;
    }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function getEntityManager($options = false) {
        #Get logger
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $options = ($options == false) ? $this->getOptions() : $options;
        $this->log->info(get_class($this) . '::getEntityManager');
        if ((null === $this->entm)) {
            $this->entm = $this->_buildEntityManager($this->getOptions());
        }
        $this->getBootstrap()->getContainer()->set('entity.manager', $this->entm);
        return $this->entm;
    }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function getCacheManager($options = false) {
        #Get logger
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $options = ($options == false) ? $this->getOptions() : $options;
        $this->log->info(get_class($this) . '::getCacheManager');
        if ((null === $this->cache)) {
            #Now configure doctrine cache
            if (defined('COMMAND_LINE') === true) {
                $cacheClass = 'Doctrine\Common\Cache\ArrayCache';
            } else if ('development' == APPLICATION_ENV) {
                $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ArrayCache';
            } else {
                $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ArrayCache';
            }
            #Cache Options.
            $this->cache = new $cacheClass(); //$this->_buildEntityManager($this->getOptions());
//            $this->cfg->setMetadataCacheImpl($cache);
//            #Caches for Development..
//            if (defined('COMMAND_LINE')===true) {
//                $cachedClass = 'Doctrine\Common\Cache\ArrayCache';
//                $cached = new $cachedClass();
//                $this->cfg->setQueryCacheImpl($cached);
//                $this->cfg->setResultCacheImpl($cached);
//            } else {//else if ('development' == APPLICATION_ENV) {}
//                $this->cfg->setQueryCacheImpl($cache);
//                $this->cfg->setResultCacheImpl($cache);
//            }
        }
        $this->getBootstrap()->getContainer()->set('doctrine.cache', $this->cache);
        return $this->cache;
    }

    /**
     * Get Current Context
     * @param type $name
     * @return type
     */
    public function getContext($name = 'default') {
        #Get logger
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log');
        }
        return 'default'; //new \Boilerplate\Webservice\Soap
    }

    /**
     *
     * @return type
     */
    protected function _buildConnection($name = 'default') {
        $options = $this->getOptions();
        $connectionOptions = $this->_buildConnectionOptions($name);
        #Setup configuration as seen from the sandbox application
        if ((null === $this->cfg)) {
            $this->cfg = $this->getConfig();
        }
        if ((null === $this->evtm)) {
            $this->evtm = $this->getEventManager();
        }

        return DriverManager::getConnection($connectionOptions, $this->cfg, $this->evtm);
    }

    /**
     * A method to build the connection options, for a Doctrine
     * EntityManager/Connection. Sure, we can find a more elegant solution to build
     * the connection options. A builder class could be applied. Sure you can with
     * some refactor :)
     * TODO: refactor to build some other, more elegant, solution to build the conn
     * ection object.
     * @param Array $params The options array defined in getOptions
     * @return \Doctrine\Common\EventManager Instance.
     */
    protected function _buildEventManager($name = 'default') {
        #Get logger
        $options = $this->getOptions();
        $eventManager = new EventManager();
        #Setup configuration as seen from the sandbox application
        if ((null === $this->cache)) {
            $this->cache = $this->getCacheManager();
        }

        $annotationReader = new AnnotationReader();
        #Now configure doctrine cache
        $cachedAnnotationReader = new DoctrineCachedReader(
                        $annotationReader, // use reader
                        $this->cache // and a cache driver
        );

        #TODO Loop through config to find availible listeners.
        foreach ($options['extensions']['listener'] as $listenerName => $listenerOptions) {
            $$listenerName = new $listenerOptions['driver']();
            if (isset($listenerOptions['methods']) === true) {
                foreach ($listenerOptions['methods'] as $method => $param) {
                    $$listenerName->$method($param);
                    $$listenerName->setAnnotationReader($cachedAnnotationReader);
                }
            }
            $eventManager->addEventSubscriber($$listenerName);
        }
        return $eventManager;
    }

    /**
     * A method to build the connection options, for a Doctrine
     * EntityManager/Connection. Sure, we can find a more elegant solution to build
     * the connection options. A builder class could be applied. Sure you can with
     * some refactor :)
     * TODO: refactor to build some other, more elegant, solution to build the conn
     * ection object.
     * @param Array $params The options array defined in getOptions
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    protected function _buildEntityManager() {
        $this->log->info(get_class($this) . '::buildEntityManager');
        #Options
        $options = $this->getOptions();
        $metadataDrivers = $options['orm']['manager']['metadataDrivers'][0];
        #Get data stored for each module
        $autoPaths = $this->getBootstrap()->getContainer()->get('autoload.paths');
        $entityPaths = $autoPaths->entities;
        $entityPaths = $autoPaths->entities;
        #Get defaults
        $this->cache = $this->getCacheManager();
        $this->cfg = $this->getConfig();
        $this->conn = $this->getConnection();
        $this->evtm = $this->getEventManager();

        #Set paths for all module entities, which should all have the same namespace...
        $this->cfg->setMetadataCacheImpl($this->cache);

        #Caches for Development..
        if (defined('COMMAND_LINE') === true) {
            $cachedClass = 'Doctrine\Common\Cache\ArrayCache';
            $cached = new $cachedClass();
            $this->cfg->setQueryCacheImpl($cached);
            $this->cfg->setResultCacheImpl($cached);
        } else {//else if ('development' == APPLICATION_ENV) {}
            $this->cfg->setQueryCacheImpl($this->cache);
            $this->cfg->setResultCacheImpl($this->cache);
        }

        #Proxy Configuration
        $this->cfg->setProxyDir($autoPaths->proxyPath);
        $this->cfg->setProxyNamespace('Proxies');
        $this->cfg->setAutoGenerateProxyClasses(false);

        $this->log->debug(get_class($this) . '::buildEntityManager(' . get_class($this->cache) . ')');

        #Driver Configuration
        AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
        AnnotationRegistry::registerAutoloadNamespace("Gedmo", CORE_PATH . "/private/library");
        AnnotationRegistry::registerAutoloadNamespace("Wednesday", CORE_PATH . "/private/library");
        AnnotationRegistry::registerAutoloadNamespace("Doctrine\ORM\Mapping\\", CORE_PATH . "/private/library");
        #Aliases interesting...
        //$annotationReader->setAnnotationNamespaceAlias('Gedmo\Mapping\Annotation\\', 'gedmo');
        #Add paths from ini.
        foreach ($metadataDrivers['mappingDirs'] as $folder) {
            if (isset($listenerOptions['path'])) {
                array_push($entityPaths, $folder);
            }
        }
        #Now configure doctrine annotation reader
        $annotationReader = new AnnotationReader();
//        #Now configure doctrine cached annotation reader
//        $cachedAnnotationReader = new DoctrineCachedReader(
//            $annotationReader, // use reader
//            $this->cache // and a cache driver
//        );

        $defaultDriverImpl = ORMAnnotationDriver::create($entityPaths, $annotationReader);
        $listenerPaths = array();
        #Add paths for Listeners.
        foreach ($options['extensions']['listener'] as $listenerName => $listenerOptions) {
            if (isset($listenerOptions['path'])) {
                array_push($listenerPaths, $listenerOptions['path']);
            }
        }
//        $gedmoDriverImpl = $this->cfg->newDefaultAnnotationDriver($listenerPaths);
        $gedmoDriverImpl = ORMAnnotationDriver::create($listenerPaths, $annotationReader);

        $chainDriverImpl = new DoctrineDriverChain();
        $defaultDriverImpl->getAllClassNames();
        foreach ($metadataDrivers['mappingNamespace'] as $mapping) {
            $chainDriverImpl->addDriver($defaultDriverImpl, $mapping . '\\');
        }
        #TODO Add namespaces from ini.
        foreach ($options['extensions']['listener'] as $listenerName => $listenerOptions) {
            if (isset($listenerOptions['namespace'])) {
                $chainDriverImpl->addDriver($gedmoDriverImpl, $listenerOptions['namespace']);
            }
        }

        $this->cfg->setMetadataDriverImpl($chainDriverImpl);
        $this->log->debug(get_class($this) . '::buildEntityManager()');
        #setup entity manager
        return EntityManager::create(
            $this->conn, $this->cfg, $this->evtm
        );
    }

    /**
     * A method to build the connection options, for a Doctrine
     * EntityManager/Connection. Sure, we can find a more elegant solution to build
     * the connection options. A builder class could be applied. Sure you can with
     * some refactor :)
     * TODO: refactor to build some other, more elegant, solution to build the conn
     * ection object.
     * @param Array $options The options array defined on the application.ini file
     * @return Array
     */
    protected function _buildConnectionOptions($name = 'default') {
        $this->log->debug(get_class($this) . '::_buildConnectionOptions(' . $name . ')');

        $options = $this->getOptions();
        if ($name == 'default') {
            if (isset($options['orm']['manager']['connection']) === true) {
                $name = $options['orm']['manager']['connection'];
            }
        }
        $connectionSpec = array(
            'pdo_sqlite' => array('path', 'memory', 'user', 'password'),
            'pdo_mysql' => array('user', 'password', 'host', 'port', 'dbname', 'unix_socket', 'charset', 'persistent'),
            'pdo_pgsql' => array('user', 'password', 'host', 'port', 'dbname', 'persistent'),
            'pdo_oci' => array('user', 'password', 'host', 'port', 'dbname', 'charset', 'persistent')
        );
        $dbalopts = $options['dbal'][$name];
        $connection = array('driver' => $dbalopts['driver']);
        //$this->log->debug(print_r($name,true));
        #Simple array map.
        foreach ($connectionSpec[$dbalopts['driver']] as $driverOption) {
            if (isset($dbalopts[$driverOption]) && !is_null($driverOption)) {
                $connection[$driverOption] = $dbalopts[$driverOption];
            }
        }
        return $connection;
    }

    /**
     * Set the bootstrap to which the resource is attached
     *
     * @param  Zend_Application_Bootstrap_Bootstrapper $bootstrap
     * @return Zend_Application_Resource_Resource
     */
    public function setBootstrap(ZendApplicationBootstrapper $bootstrap) {
        $this->_bootstrap = $bootstrap;
        return $this;
    }

    /**
     * Retrieve the bootstrap to which the resource is attached
     *
     * @return null|Zend_Application_Bootstrap_Bootstrapper
     */
    public function getBootstrap() {
        if (null === $this->_bootstrap) {
            $this->_bootstrap = Front::getInstance()->getParam('bootstrap');
        }
        return $this->_bootstrap;
    }

    /**
     * Set options from array
     *
     * @param  array $options Configuration for resource
     * @return Zend_Application_Resource_ResourceAbstract
     */
    public function setOptions(array $options) {
        if (array_key_exists('bootstrap', $options)) {
            $this->setBootstrap($options['bootstrap']);
            unset($options['bootstrap']);
        }

        foreach ($options as $key => $value) {
            if (in_array(strtolower($key), $this->_skipOptions)) {
                continue;
            }

            $method = 'set' . strtolower($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        $this->_options = $this->mergeOptions($this->_options, $options);

        return $this;
    }

    /**
     * Retrieve resource options
     *
     * @return array
     */
    public function getOptions() {
        return $this->_options;
    }

    /**
     * Merge options recursively
     *
     * @param  array $array1
     * @param  mixed $array2
     * @return array
     */
    public function mergeOptions(array $array1, $array2 = null) {
        if (is_array($array2)) {
            foreach ($array2 as $key => $val) {
                if (is_array($array2[$key])) {
                    $array1[$key] = (array_key_exists($key, $array1) && is_array($array1[$key])) ? $this->mergeOptions($array1[$key], $array2[$key]) : $array2[$key];
                } else {
                    $array1[$key] = $val;
                }
            }
        }
        return $array1;
    }

    /**
     *
     */
    public function getConfig() {
        if ((null === $this->cfg)) {
            $this->cfg = new ORMConfiguration();
            #Set Logging
            $logger = new DBALDebugStack;
            $this->cfg->setSqlLogger($logger);
        }
        return $this->cfg;
    }

}
