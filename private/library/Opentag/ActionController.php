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
    Doctrine\Common\PropertyChangedListener,
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

        #Render log
        $this->_log = $this->getLog();
        #Render cfg
        $this->_cfg = $this->getConfiguration($context,$this->getRequest());
        #Render doctrine
        $this->doctrine = $this->getDoctrine($context);//$this->_cfg, $this->_log
        #Render evtn
        $this->_evtm = $this->doctrine->getEventManager($context);
        #Render entm
        $this->_entm = $this->doctrine->getEntityManager($context);
        #Render entity
//        $this->view->entity = $this->doctrine->getResponseEntity($this->getRequest());
        #Set layout files to look in Theme folder.
        $this->view->layout()->setLayoutPath(WEB_PATH . '/assets/layouts')
                ->setLayout('bootstrap')
                ->setViewBasePath(WEB_PATH . '/assets/views');
        #Set view files based on template, theme & config settings.
        $this->view->addScriptPath(WEB_PATH . '/assets/views');
        #Detect Ajax Early.
        if ($this->_request->isXmlHttpRequest()) {
            $this->getLayout()->disableLayout();
        }
        #Default View Setup
        $this->view->doctype('<!DOCTYPE html>');
        #Render Sidebar
//        $this->view->layout()->sidebar = "<h4>Sidebar</h4>";
        #Init Context Switching
        $this->_helper->contextSwitch()->setContext(
                'html', array(
            'suffix' => 'html',
            'headers' => array(
                'Content-Type' => 'text/html; Charset=' . $encoding,
            ),
                ), 'xml', array(
            'suffix' => 'xml',
            'headers' => array(
                'Content-Type' => 'text/xml; Charset=' . $encoding,
            ),
                ), 'json', array(
            'suffix' => 'json',
            'headers' => array(
                'Content-Type' => 'application/json; Charset=' . $encoding,
            ),
                )
        )->setAutoJsonSerialization(false)->initContext();

//        $this->buildPageTitle();
//        $this->buildPageKeywords();
//        $this->buildPageDescription();
//        $this->buildHeadMeta($encoding, $this->locale);
//        $this->buildScripts();
//        $this->buildGoogleAnalytics();
//        $this->addFacebookOpenGraph();
//        $this->view->placeholder('doctrine')->exchangeArray(array('em'=>$this->em));
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
        $this->view->articles = (isset($this->entm)===false)?"none found. ":$this->entm->getRepository()->findAll();
    }

    public function propertyChanged($sender, $propertyName, $oldValue, $newValue) {

    }

    public function getResponseEntity($request) {

    }

    public function getClientResponsePrefs($request) {

    }

    public function getConfiguration($context, $request) {
        $cfg = false;
        return $cfg;
    }

    public function getDoctrine($context) {
        $this->_cfg = $config;
        $this->_log = $log;

        $doctrine = false;
        return $doctrine;
    }

    public function getEventManager($context) {
        $evtm = false;
        return $evtm;
    }

    public function getEntityManager($context) {
        $log = false;
        return $entm;
    }

    public function getLog() {
        $log = false;
        return $log;
    }
}