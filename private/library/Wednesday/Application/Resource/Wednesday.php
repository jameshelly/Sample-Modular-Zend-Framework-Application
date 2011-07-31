<?php
//namespace Wednesday\Application\Resource;
/*
use Doctrine\ORM\EntityManager,
    Wednesday\Auth\Adapter\Doctrine,
    Wednesday\Restable\RestListener,
    Gedmo\Tree\TreeListener,
    Gedmo\Loggable\LoggableListener,
    Gedmo\Translatable\TranslationListener,
    Doctrine\ORM\Configuration;
*/

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Description of Wednesday
 *
 * @author mrhelly
 */
class Wednesday_Application_Resource_Wednesday extends \Zend_Application_Resource_ResourceAbstract {

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $entitymanager;
    
    /**
     *
     * @var array 
     */
    protected $options;
    
    /**
     *
     * @var \Wednesday\Site 
     */
    protected $site;
    
    /**
     *
     * @var \Wednesday\Template 
     */
    protected $template;
    
    /**
     *
     * @var Zend_Config_Ini 
     */
    protected $theme;
    
    /**
     *
     * @var Zend_Log 
     */
    protected $logger;

    /**
     * Init Wednesday Manager (Themes|Templates|Acl|Session|Cache|Entities).
     *
     * @param N/A
     * @return $this;
     */
    public function init() {
        #Get logger
        $this->logger = $this->getBootstrap()->getContainer()->get('logger');
	$this->entitymanager = $this->getBootstrap()->getContainer()->set('entity.manager');
	$this->template = $this->getBootstrap()->getContainer()->set('template.manager');
        $this->logger->info(get_class($this).'::init');
	return $this;
    }
    
    
}
