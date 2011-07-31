<?php
//namespace Wednesday\Rest;

use Doctrine\ORM\EntityManager,
    Wednesday\Rest,
    Wednesday\Restable\RestListener,
    Gedmo\Tree\TreeListener,
    Doctrine\ORM\Configuration;

/**
 * Wednesday_Rest_Controller - The rest error controller class
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Wednesday_Rest_Controller extends Zend_Rest_Controller
{
    /**
     *
     * Zend_Auth object
     * @var Zend_Auth
     */
    protected $auth;

    /**
     *
     * Auto Loaded acl object to filter program flow.
     * @var Wednesday_Application_Resource_AccessControlList
     */
    protected $acl;

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
     * Access to Zend_Config_Ini.
     * @var Zend_Config_Ini
     */
    public $config;


    /**
     * Store bookstrap
     * @see Controller/Zend_Controller_Action::preDispatch()
     */
    public function preDispatch() {
    	#init live plugins.
    	//$bootstrap = $this->getInvokeArg('bootstrap');
		//$bootstrap->getContainer()->get('acl.manager')->getAclResource($this->getRequest());
    }

    /**
     * This action handles
     *    - Default Action Initialisation
     */
    public function init() {
    	#Get bootstrap object.
        $bootstrap = $this->getInvokeArg('bootstrap');
        #Get Logger
        $this->log = $bootstrap->getContainer()->get('logger');
        #Get bootstrap object.
        $this->config = $bootstrap->getContainer()->get('config');
    	#Get Doctrine Entity Manager
        $this->em = $bootstrap->getContainer()->get('entity.manager');
        #Security Options ::
        #Get Acl Object
        //$this->acl = $bootstrap->getContainer()->get('acl.manager');
        //$this->acl->getAclResource($this->getRequest());
        #Get Zend Auth.
        $this->auth = Zend_Auth::getInstance();
        if (!$this->auth->hasIdentity()) {
            $this->_redirect('/auth/login/');
                    //_forward('login', 'auth', 'default');
        }
        #Security ::
        #Setup styles & output variables.
        //$this->view->layout()->page_title = "Site Title";
        #Setup default view settings.
        //$charset = 'utf-8';
        //$date = new Zend_Date();
        #Navigation TODO Add dynamic generation. +acl filtering.
        //$this->view->navigation = $this->setNavigationContainer();
        //$this->view->menu = $this->renderNavigation();
        #Footer placeholders.
        /*
        $this->view->placeholder('copyright')->append('2011 Wednesday');
        $this->view->placeholder('company-name')->append('Wednesday London');
        $this->view->placeholder('company-street-address')->append('Biscuit Building');
        $this->view->placeholder('company-extended-address')->append('10 Redchurch Street');
        $this->view->placeholder('company-locality')->append('Shorditch');
        $this->view->placeholder('company-region')->append('London');
        $this->view->placeholder('company-postal-code')->append('E2 7DD');
        $this->view->placeholder('company-country')->append('United Kingdom');
        $this->view->placeholder('company-telephone')->append('+ 44 20 7033 7731');
        $this->view->placeholder('company-email')->append('info@wednesday-london.com');
        */
        $method = 'listAction';
        $method = ($this->getRequest()->isGet())?'readAction':$method;
        $method = ($this->getRequest()->isPost())?'createAction':$method;
        $method = ($this->getRequest()->isPut())?'updateAction':$method;
        $method = ($this->getRequest()->isDelete())?'deleteAction':$method;
        $method = ($this->getRequest()->isHead())?'head':$method;
        $method = ($this->getRequest()->isOptions())?'option':$method;
        $this->method = $method;
//parent::init();
        //*
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch
            ->addActionContext('index', array('xml', 'json'))
            ->addActionContext('get', array('xml', 'json'))
            ->addActionContext('put', array('xml', 'json'))
            ->addActionContext('post', array('xml', 'json'))
            ->addActionContext('delete', array('xml', 'json'))
            ->setAutoJsonSerialization(true)
            ->initContext();
        //*/
        //disable view rendering
        //$this->_helper->viewRenderer->setNeverRender(true);
        $this->_helper->viewRenderer->setNoRender(true);
        //disable layout (if you use layouts)
        $this->_helper->layout->disableLayout();
        
        $this->log->debug(get_class($this).'::init()');
    }
    
    public function respondError($message='Internal Server Error', $code=500) {
           //$this->view->clearVars();
           $this->view->response = (object) array( 'status' => false, 'code' => $code, 'message' => $message);/*(object) array()*/
    }
    
    public function indexAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("(".$this->method.") From indexAction('".get_class($this)."') returning all Data:".$return);
    }

    public function getAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("(".$this->method.") From getAction('".get_class($this)."') returning the requested Data:".$return);
    }

    public function postAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("(".$this->method.") From postAction('".get_class($this)."') creating the requested Data:".$return);
    }

    public function putAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("(".$this->method.") From putAction('".get_class($this)."') updating the requested Data:".$return);
    }

    public function deleteAction() {
    	$params = $this->_getAllParams();
    	$return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("(".$this->method.") From deleteAction('".get_class($this)."') deleting the requested Data:".$return);
    }
}
