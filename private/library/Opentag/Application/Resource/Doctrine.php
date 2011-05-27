<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';


use Doctrine\ORM\EntityManager,
	Opentag\Restable\RestListener,
    Gedmo\Tree\TreeListener,
    Doctrine\ORM\Configuration;

class Opentag_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var Zend_Log
     */
    protected $log;

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function init() {

        #Get logger
        $this->log = $this->getBootstrap()->getContainer()->get('logger');
        $this->log->info(get_class($this).'::init');
        if (null !== ($em = $this->getEntityManager())) {
            return $em;
        }
    }

    /**
     * Init Doctrine
     *
     * @param N/A
     * @return \Doctrine\ORM\EntityManager Instance.
     */
    public function getEntityManager() {
        #Get logger
        //$logger = Zend_Registry::get('logger');
        if(null === $this->log) {
            $this->log = $this->getBootstrap()->getContainer()->get('logger');
        }
        $this->log->info(get_class($this).'::getEntityManager');
        if ( (null === $this->_em) ) {
            $this->_em = $this->_buildEntityManager($this->getOptions());
        }
        $this->getBootstrap()->getContainer()->set('entity.manager', $this->_em);
        return $this->_em;
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
    protected function _buildEntityManager(array $params) {
        $this->log->info(get_class($this).'::buildEntityManager');

        #Options
        $options = $params;
        //die(print_r($options));
        $connectionOptions = $this->_buildConnectionOptions($params);

        #Setup configuration as seen from the sandbox application
        $config = new \Doctrine\ORM\Configuration;
        $eventManager = new \Doctrine\Common\EventManager();        
/*         $eventManager->addEventSubscriber(new VersionListener()); */
/*         $eventManager->addEventSubscriber(new RestListener()); */
        $eventManager->addEventSubscriber(new TreeListener());
        $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionOptions, $config, $eventManager);

        #Now configure doctrine cache
        if ('development' == APPLICATION_ENV) {
            $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ArrayCache';
        } else {
            $cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\XcacheCache';
        }
        $cache = new $cacheClass();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        
        $autoPathes = $this->getBootstrap()->getContainer()->get('autoload.pathes');

        #Proxy Configuration
        $config->setProxyDir($autoPathes->proxyPath);
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(true);

        #Set Logging
        $logger = new \Doctrine\DBAL\Logging\DebugStack;
        $config->setSqlLogger($logger);

        #Driver Configuration
        $driver = \Doctrine\ORM\Mapping\Driver\AnnotationDriver::create($autoPathes->entities);
        $driver->getAllClassNames();
        $config->setMetadataDriverImpl($driver);

        #setup entity manager
        $em = \Doctrine\ORM\EntityManager::create(
            $connection,
            $config,
            $eventManager
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
    protected function _buildConnectionOptions(array $options) {
        $connectionSpec = array(
            'pdo_sqlite' => array('user', 'password', 'path', 'memory'),
            'pdo_mysql'  => array('user', 'password', 'host', 'port', 'dbname', 'unix_socket'),
            'pdo_pgsql'  => array('user', 'password', 'host', 'port', 'dbname'),
            'pdo_oci'    => array('user', 'password', 'host', 'port', 'dbname', 'charset')
        );
		$dbalopts = $options['dbal'][$options['orm']['manager']['connection']];
        $connection = array('driver' => $dbalopts['driver'] );

		#Simple array map.
        foreach ($connectionSpec[$dbalopts['driver']] as $driverOption) {
            if (isset($dbalopts[$driverOption]) && !is_null($driverOption)) {
                $connection[$driverOption] = $dbalopts[$driverOption];
            }
        }

		/*
		if (isset($dbalopts['driverOptions']) && !is_null($dbalopts['driverOptions'])) {
		    $connection['driverOptions'] = $dbalopts['driverOptions'];
		}
		*/

        return $connection;
    }
}