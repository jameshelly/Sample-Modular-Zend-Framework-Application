<?php
//namespace Application;
#Come on ZF2!
use \Zend\Di\Di as DependencyInjector;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{

    /*
     * _initConfig
     *
     * Initializes the config
     *
     * @param void
     * @return void
     */
    protected function _initConfig()
    {
        #Store Config to container, so we can easily use it later.
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/default.ini', APPLICATION_SRV);
        $this->setAppNamespace($config->appnamespace);
        $this->getContainer()->set('config', $config);
    }

    /*
     * _initDate
     *
     * Initializes the default timezone for the php ENV
     *
     * @param void
     * @return void
     */
    protected function _initDate() {
        $config = $this->getContainer()->get('config');
    	date_default_timezone_set($config->settings->application->datetime);
    }

    /*
     * _initLog
     *
     * Initializes Logging.
     *
     * @param void
     * @return void
    */
    protected function _initLog() {
        #Log, we need to log.
        $config = $this->getContainer()->get('config');
        $logPath = $config->resources->log->path;
        $filelog = new Zend_Log_Writer_Stream($logPath);
        $filter = new Zend_Log_Filter_Priority(Zend_Log::INFO);
        $filelog->addFilter($filter);
        $logger = new Zend_Log($filelog);
        if(APPLICATION_ENV != 'production') {
            $writer = new Zend_Log_Writer_Firebug();
            $fbfilter = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
            $writer->addFilter($fbfilter);
            $logger->addWriter($writer);
        }
        $logger->info(get_class($this).'::_initLog['.APPLICATION_ENV.']');
        $this->getContainer()->set('logger', $logger);
    }

    /**
     * _initServices
     *
     * Initializes the Dependancy Injection Service.
     *
     * @param void
     * @return void
     */
    protected function _initServices() {
        $logger = $this->getContainer()->get('logger');
        $di = new DependencyInjector();
        $logger->info(get_class($this).'::_initServices()');
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/services.xml');
        foreach($config->services->service as $service) {
            $logger->info($service->class."-".$service->argument);
//            $optsArr = explode('.', $service->argument);
//            $config['resources']['doctrine']
//            $di->instanceManager()->setParameters($service->class,$service->argument);
        }
        $logger->info(get_class($this).'::_initServices()');
        Zend_Registry::set('sc', $di);
    }


    /*
     * _initRoutes
     *
     * Initializes Application Routes.
     *
     * @param void
     * @return void
    */
    protected function _initRoutes()
    {
    	#Set up router.
    	$this->bootstrap('FrontController');
    	$front = $this->getResource('FrontController');
        $router = $front->getInstance()->getRouter();
        #Each Module can add to the router.
        foreach ($front->getControllerDirectory() as $module => $path) {
            $modulePath = dirname($path);
            $configPath = $modulePath.'/configs/default.ini';
            $moduleRoutes = new Zend_Config_Ini($configPath, 'default');
            if($moduleRoutes->module->enabled) {
            	$router->addConfig($moduleRoutes, 'routes');
            }
        }
        #Single Route ini.
        //$routescfg = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', APPLICATION_SRV);
        //$router->addConfig($routescfg, 'routes');
    }

    /*
     * _initAutoload
     *
     * Initializes Autoloading for modules, entities, proxies & forms.
     *
     * @param void
     * @return void
     */
    protected function _initAutoload() {
        $front = $this->getResource('FrontController');
        $config = $this->getContainer()->get('config');

        $proxyPaths = array();
        $proxyPath = APPLICATION_PATH . $config->resources->doctrine->orm->manager->proxy->dir;
        $entitiesPaths = array();
        $formPaths = array();

        #This is a fallback to autoload our own classes in library
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->setFallbackAutoloader(true);
        foreach ($front->getControllerDirectory() as $module => $path) {
            #dirname goes back a folder from the controller directory so we have the root folder of the module.
            $modulePath = dirname($path);
            $configPath = $modulePath.'/configs/default.ini';
            $moduleConfig = new Zend_Config_Ini($configPath, 'default');
        	if($moduleConfig->module->enabled == true) {
	            $entitiesPaths[] = $modulePath.'/entities';
	            $proxyPaths[] = $modulePath.'/entities/generated';
	            $formPaths[] = $modulePath.'/forms';
	            #TODO Move this lot to bootstrap, and get doctrine to load the proxy & entity pathes from a container.
	            #This is for loading form classes in the specified module. Modules MUST be stored in a folder of the same name as the class prefix.
	            #for windows ensure that there are no '\' before explode.
	            $modulePath = str_replace(DIRECTORY_SEPARATOR,'/',$modulePath);
	            $exPaths = explode('/',$modulePath);
	            $moduleName = ucfirst($exPaths[count($exPaths)-1]);
	            $autoloader = new Zend_Application_Module_Autoloader(array(
	                'namespace' => $moduleName.'_',
	                'basePath'  => $modulePath,
	                'resourceTypes' => array(
	                    'form' => array('path'=>'forms/', 'namespace'=>'Form'),
	                    'plugin' => array('path'=>'plugins/', 'namespace'=>'Plugin')
	                )
	            ));
			}
        }
        $autoloader = (object) array(
            'entities'=>$entitiesPaths,
            'proxies'=>$proxyPaths,
            'proxyPath'=>$proxyPath,
            'forms'=>$formPaths
        );
        #Store Pathes for later use.
        $this->getContainer()->set('autoload.paths', $autoloader);
    }
    //*/

    /**
     * _initDate
     *
     * Initializes the default timezone for the php ENV
     *
     * @param void
     * @return void
     */
    protected function _initExamplePlugin() {
    	$logger = $this->getContainer()->get('logger');
        Zend_Controller_Front::getInstance()->registerPlugin(new Default_Plugin_Example());
     	$logger->info(get_class($this).'::_initExamplePlugin[]');
    }

    /*
     * _initZFDebug
     *
     * Add ZFDebug
     *
     * @param dfg
     * @return void
    protected function _initZFDebug() {
        $logger = $this->getContainer()->get('logger');
        if(APPLICATION_ENV != 'production'){
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace('ZFDebug');
            $config = $this->getContainer()->get('config');
            $options = array(
                'plugins' => array(
                    'Opentag_ZFDebug_Plugin_Doctrine',
                    'Variables',
                        'File' => array('base_path' => APPLICATION_PATH . '/../','library' => $config->AutoloaderNamespaces->toArray() ),
                        'Memory',
                        'Time',
                        'Log',
                        'Exception'
                )
            );
            # Instantiate the doctrine database adapter and setup the plugin.
            if ($this->hasPluginResource('doctrine')) {
		$this->bootstrap('doctrine');
                $em = $this->getPluginResource('doctrine')->getEntityManager();
                $options['plugins']['Opentag_ZFDebug_Plugin_Doctrine']['adapter'] = $em;
            }
            # Setup the cache plugin
            if ($this->hasPluginResource('cache')){
                $this->bootstrap('cache');
                $cache = $this->getPluginResource('cache')->getDbAdapter();
                $options['plugins']['Cache']['backend'] = $cache->getBackend();
            }
            $debug = new ZFDebug_Controller_Plugin_Debug($options);
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);
            $logger->info(get_class($this).'::_initZFDebug[]');
        }
    }
  //*/
}
