<?php
namespace Opentag;

/**
 * Description of Controller
 *
 * @author mrhelly
 */
use Doctrine\ORM\EntityManager,
    Opentag\ControllerInterface as ControllerI,
//    Application\Forms,
//    Application\Entities,
    Opentag\Service\Doctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugPlugin,
    Zend_Controller_Action as ControllerAction,
    Zend\Di\Configuration,
    Zend\Di\ServiceLocation,
    Zend_Auth_Result;

class ActionController extends ControllerAction implements ControllerI {

    const PAGES = "Application\Entities\Pages";
    const TEMPLATES = "Application\Entities\Templates";
    const RENDERERS = "Application\Entities\Renders";
    const ARTICLES = "Application\Entities\Articles";
    const USERS = "Application\Entities\Users";
    const EVENTS = "Application\Entities\Events";
    const SETTINGS = "Application\Entities\Settings";
    const METADATA = "Application\Entities\Metadata";
    const TAXONOMY = "Application\Entities\Taxonomy";
    const CATEGORIES = "Application\Entities\Categories";
    const TAGS = "Application\Entities\Tgs";

    /**
     * @var DoctrineService
     */
    protected $doctrine;

    protected $_entm;
    protected $_evtm;
    protected $_conn;
    protected $_log;
    protected $_cfg;

    public function init()
    {
    	#Get Doctrine Entity Manager
        $bootstrap = $this->getInvokeArg('bootstrap');
        $context = 'bootstrap';
        $this->_log = $this->getLog();
        $this->_cfg = $this->getConfiguration($context,$this->getRequest());
        $this->doctrine = $this->getDoctrine($context);//$this->_cfg, $this->_log
        $this->_evtm = $this->doctrine->getEventManager($context);
        $this->_entm = $this->doctrine->getEntityManager($context);
        $this->view->entity = $this->doctrine->getResponseEntity($this->getRequest());
//        $this->em = $bootstrap->getContainer()->get('entity.manager');
        //$this->view->placeholder('doctrine')->exchangeArray(array('em'=>$this->em));
//        $zfDebugPluginInstance = new ZFDebugPlugin($initArray);
        $this->view->partialLoop()->setObjectKey('entity');
        $this->view->headTitle('blog');
        $this->view->response = "";
    }

    public function indexAction()
    {
        //getResponse($this->getClientResponsePrefs()) = $this->entm->getRequestEntity($this->getRequest());
        $this->view->entity = "";
        // action body
        $this->view->title = "Default Title";
        $this->view->message = "Default Message";
        $this->view->content = "<p>Default Content</p>";
        $this->view->articles = $this->em->getRepository()->findAll();
    }

    protected function getResponseEntity($request) {

    }

    protected function getClientResponsePrefs($request) {

    }

    protected function getConfiguration($context, $request) {
        $cfg = false;
        return $cfg;
    }

    protected function getDoctrine($context) {
        $this->_cfg = $config;
        $this->_log = $log;

        $doctrine = false;
        return $doctrine;
    }

    protected function getEventManager($context) {
        $evtm = false;
        return $evtm;
    }

    protected function getEntityManager($context) {
        $log = false;
        return $entm;
    }

    protected function getLog() {
        $log = false;
        return $log;
    }
}