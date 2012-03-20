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
    Doctrine\DBAL\Connection,
    Doctrine\ORM\EntityManager,
    Doctrine\OXM\XmlEntityManager,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver as ORMAnnotationDriver,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver as ODMAnnotationDriver,
    Doctrine\OXM\Mapping\Driver\AnnotationDriver as OXMAnnotationDriver,
    Doctrine\ORM\Mapping\Driver\DriverChain as DoctrineDriverChain,
    Doctrine\DBAL\Logging\DebugStack as DoctrineDebugStack,
    Opentag\Auth\Adapter\Doctrine as AuthAdapterDoctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugDoctrine,
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
    protected $_skipOptions = array(
        'options',
        'config',
    );

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
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } else if ($options instanceof Zend_Config) {
            $this->setOptions($options->toArray());
        }

        $CacheOptions = array();
        $ConnectionOptions = array();
        $EventOptions = array();
        $EntityOptions = array();
        $XmlEntityOptions = array();
        $DocumentOptions = array();

        $this->context      = $this->getContext();
        $this->cache        = $this->getCacheManager($CacheOptions);
        $this->conn         = $this->getConnection($ConnectionOptions);
        $this->evtm         = $this->getEventManager($EventOptions);
        $this->evtm         = $this->getEntityManager($EntityOptions);
        $this->xmltm        = $this->getXmlEntityManager($XmlEntityOptions);
        $this->xmltm        = $this->getDocumentManager($DocumentOptions);
    }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function init() {
        $this->_bootstrap = Front::getInstance()->getParam('bootstrap');
        if(null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log');
        }

        return $this;
    }

    /**
     * Set options from array
     *
     * @param  array $options Configuration for resource
     * @return Zend_Application_Resource_ResourceAbstract
     */
    public function setOptions(array $options)
    {
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
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Merge options recursively
     *
     * @param  array $array1
     * @param  mixed $array2
     * @return array
     */
    public function mergeOptions(array $array1, $array2 = null)
    {
        if (is_array($array2)) {
            foreach ($array2 as $key => $val) {
                if (is_array($array2[$key])) {
                    $array1[$key] = (array_key_exists($key, $array1) && is_array($array1[$key]))
                                  ? $this->mergeOptions($array1[$key], $array2[$key])
                                  : $array2[$key];
                } else {
                    $array1[$key] = $val;
                }
            }
        }
        return $array1;
    }

//    public function getConnection() {
//
//    }
    /**
     *
     * @param type $name
     * @return type
     */
    public function getConnection($name = 'default') {
        #Get logger
        if(null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log');//$this->getBootstrap()->getResource('Log');//->getContainer()->get('logger');
        }
        $options = $this->getOptions();
//        $name = (($name != $options['orm']['manager']['connection'])&&($name == 'default'))?$options['orm']['manager']['connection']:$name;
        $this->log->debug(get_class($this).'::getConnection('.$name.')');
        if(($name != 'default')&&($name != '')) {
            return $this->_buildConnection($name);
        }
        if ( (null === $this->_conn) ) {
            $this->_conn = $this->_buildConnection($name);
        }
        $this->getBootstrap()->getContainer()->set('doctrine.connection', $this->_conn);
        return $this->_conn;
    }

    /**
     *
     */
    public function getContext($name = 'default') {
        if(null === $this->log) {
            $this->log = $this->getBootstrap()->getResource('Log');
        }
        return 'default';//new \Boilerplate\Webservice\Soap
    }

//
//
//    public function getEventManager() {
//
//    }
   public function getEventManager($options = false) {
        #use apc object cache.
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $this->log->info(get_class($this) . '::getEventManager');
        if ((null === $this->evtm)) {
            $this->evtm = $this->_buildEventManager($this->getOptions());
        }
        $this->getBootstrap()->getContainer()->set('event.manager', $this->entm);
        return $this->evtm;
    }

//
//    public function getEntityManager() {
//
//    }
   /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function getEntityManager($options = false) {
        #Get logger
        //$logger = Zend_Registry::get('logger');
        #use apc object cache.
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $this->log->info(get_class($this) . '::getEntityManager');
        if ((null === $this->entm)) {
            $this->entm = $this->_buildEntityManager($this->getOptions());
        }
        $this->getBootstrap()->getContainer()->set('entity.manager', $this->entm);
        return $this->entm;
    }


//
//    public function getEntityManager() {
//
//    }
   /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function getCacheManager($options = false) {
        #Get logger
        //$logger = Zend_Registry::get('logger');
        #use apc object cache.
        if (null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
         $this->log->info(get_class($this) . '::getEntityManager');
        if ((null === $this->entm)) {
            $this->entm = $this->_buildEntityManager($this->getOptions());
        }
        $this->getBootstrap()->getContainer()->set('entity.manager', $this->entm);
        return $this->entm;
   }

    /**
     *
     * @return type
     */
    protected function _buildConnection($name = 'default') {
        $options = $this->getOptions();
        $connectionOptions = $this->_buildConnectionOptions($name);
        #Setup configuration as seen from the sandbox application
        if ( (null === $this->evtm) ) {
            $this->evtm = $this->getEventManager();
        }
        return $this->cahce;
    }

//    public function _buildEventManager($options = false) {
//        return new \Doctrine\Common\EventManager();
//    }
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
        $options = $this->getOptions();
        $eventManager = new EventManager();
//        $this->log->debug($options);
        #TODO Loop through config to find availible listeners.
        foreach ($options['extensions']['listener'] as $listenerName => $listenerOptions) {
              $$listenerName = new $listenerOptions['driver']();
              if(isset($listenerOptions['methods'])===true) {
                  foreach($listenerOptions['methods'] as $method => $param) {
                      $$listenerName->$method($param);
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
        $connection = $this->_buildConnection();
        $config = $this->cfg;
        $eventManager = $this->evtm;

        #Now configure doctrine cache
        if ('development' == APPLICATION_ENV) {
            $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ArrayCache';
        } else {
            $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\XcacheCache';
        }

        #Cache Options.
        $cache = new $cacheClass();
        $config->setMetadataCacheImpl($cache);
        #Caches for Development..
        if ('development' == APPLICATION_ENV) {
            $cachedClass = 'Doctrine\Common\Cache\ArrayCache';
            $cached = new $cachedClass();
            $config->setQueryCacheImpl($cached);
            $config->setResultCacheImpl($cached);
        } else {
            $config->setQueryCacheImpl($cache);
            $config->setResultCacheImpl($cache);
        }
        $autoPaths = $this->getBootstrap()->getContainer()->get('autoload.paths');

        #Proxy Configuration
        $config->setProxyDir($options['orm']['manager']['proxy']['dir']);
        $config->setProxyNamespace($options['orm']['manager']['proxy']['namespace']);
        $config->setAutoGenerateProxyClasses($options['orm']['manager']['proxy']['autoGenerateClasses']);

        #Set Logging
        $logger = new DoctrineDebugStack;
        $config->setSqlLogger($logger);

        #Driver Configuration
        AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
        AnnotationRegistry::registerAutoloadNamespace("Gedmo", CORE_PATH . "/private/library");
        AnnotationRegistry::registerAutoloadNamespace("Doctrine\ORM\Mapping\\", CORE_PATH . "/private/library");
//        AnnotationRegistry::registerAutoloadNamespaces(array("Gedmo\Mapping\Annotation\\","Doctrine\ORM\Mapping\\"));
        $reader = new AnnotationReader();
        $reader->setAnnotationNamespaceAlias('Gedmo\Mapping\Annotation\\', 'gedmo');
//        $reader->setIgnoreNotImportedAnnotations(true);
//        $reader->setEnableParsePhpImports(false);
        $entityPathes = $autoPaths->entities;
//        array_push($entityPathes, CORE_PATH . '/private/library/Gedmo/Tree/Entity');
//        array_push($entityPathes, CORE_PATH . '/private/library/Gedmo/Sortable/Entity');
//        array_push($entityPathes, CORE_PATH . '/private/library/Gedmo/Loggable/Entity');
//        array_push($entityPathes, CORE_PATH . '/private/library/Gedmo/Translatable/Entity');

        $chainDriverImpl = new DoctrineDriverChain();
        $defaultDriverImpl = AnnotationDriver::create($autoPaths->entities, $reader);
        $defaultDriverImpl->getAllClassNames();
        $translatableDriverImpl = $config->newDefaultAnnotationDriver($entityPathes);
        $chainDriverImpl->addDriver($defaultDriverImpl, 'Application\Entities\\');
//        $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Tree');
//        $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Sortable');
//        $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Loggable');
//        $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');
        $config->setMetadataDriverImpl($chainDriverImpl);

        #setup entity manager
        $em = \Doctrine\ORM\EntityManager::create(
                        $connection, $config, $eventManager
        );

        return $em;
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
        $this->log->debug(get_class($this).'::_buildConnectionOptions('.$name.')');

        $options = $this->getOptions();
        if($name == 'default') {
            if(isset($options['orm']['manager']['connection'])===true){
                $name = $options['orm']['manager']['connection'];
            }
        }
        $connectionSpec = array(
            'pdo_sqlite' => array('path', 'memory', 'user', 'password'),
            'pdo_mysql'  => array('user', 'password', 'host', 'port', 'dbname', 'unix_socket', 'charset', 'persistent'),
            'pdo_pgsql'  => array('user', 'password', 'host', 'port', 'dbname', 'persistent'),
            'pdo_oci'    => array('user', 'password', 'host', 'port', 'dbname', 'charset', 'persistent')
        );
		$dbalopts = $options['dbal'][$name];
        $connection = array('driver' => $dbalopts['driver'] );
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
    public function setBootstrap(\Zend_Application_Bootstrap_Bootstrapper $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
        return $this;
    }

    /**
     * Retrieve the bootstrap to which the resource is attached
     *
     * @return null|Zend_Application_Bootstrap_Bootstrapper
     */
    public function getBootstrap()
    {
        return $this->_bootstrap;
    }

}
