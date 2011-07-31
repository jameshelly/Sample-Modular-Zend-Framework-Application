<?php

//namespace Wednesday\Controller;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Collections\ArrayCollection,
    Gedmo\Tree\TreeListener,
    Wednesday\Restable\RestListener,
    Application\Entities\Users,
    Application\Entities\Roles,
    Application\Entities\Permissions;

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

/**
 * Description of AdminAction
 *
 * @author mrhelly
 */
class Wednesday_Controller_AdminAction extends Wednesday_Controller_Action {
    
    /**
     *
     * Zend_Auth object
     * @var Zend_Auth
     */
    protected $auth;

    /**
     * Doctrine\ORM\EntityManager object wrapping the entity environment
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * Access to Zend_Log.
     * @var Zend_Log
     */
    public $log;
    
    /**
     *
     * Wednesday Manager (Themes|Templates|Acl|Session|Cache|Entities).
     * @var Wednesday_Application_Resource_Wednesday
     */
    protected $wednesday;

    /**
     * 
     */
    public function init() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        #Get Logger
        if ($this->log = $this->getLog()) {
            $this->log->info(get_class($this).'::init()');
        } else {
            $this->log = $bootstrap->getContainer()->get('logger');
        }
	
    	#Get Doctrine Entity Manager
        $this->em = $bootstrap->getContainer()->get('entity.manager');
	
    	#Get Wednesday Manager
        //$this->wednesday = $bootstrap->getContainer()->get('wednesday.manager');
	
    	#Get Template Manager
        //$this->template = $this->wednesday->getTemplateManger($this->getRequest());
	//$this->template = $bootstrap->getContainer()->get('template.manager');
        
        #Get Acl Object
        //$this->acl = $this->wednesday->getAcl($this->getRequest());
	//$this->acl = $bootstrap->getContainer()->get('acl.manager');
        
        #Get Zend Auth.
        $this->auth = Zend_Auth::getInstance();
    
	
    }
    
    /**
     * Store bookstrap
     * @see Controller/Zend_Controller_Action::preDispatch()
     */
    public function preDispatch() {
    	#init live plugins.
	$bootstrap = $this->getInvokeArg('bootstrap');
    }
    
    
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    public function getUniqid(){
            return uniqid().dechex(rand(65536,1048574));	
    }

    
}
