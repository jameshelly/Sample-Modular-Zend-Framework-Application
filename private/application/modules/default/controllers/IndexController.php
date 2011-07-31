<?php
//namespace Application\Module\Controller;

use /*Opentag,*/
    \Doctrine\ORM\EntityManager,
    \Zend_Controller_Action,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;
/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class IndexController extends \Zend_Controller_Action {

     /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    public function init() {
        //$this->_loginForm = new Application_Form_Login();
        //$this->view->form = $this->_loginForm;
        // get auth service from bootstrap
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_auth = $bootstrap->getResource('auth');
    }

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function indexAction() {
    	$this->view->message = "index";
    	$this->view->placeholder('htmlclasses')->append('template-full-screen');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('/themes/moncler/css/homepage-backgrounds.css'));
		/*
		//$fullUrl = $this->getRequest()->getHttpHost() .urldecode($this->view->url());
		
		$pageParts=explode('/',urldecode($this->view->url()));
		
		$pageAlias=array_pop($pageParts);
		$pageName="not found";
		$firstRoute='';
		array_shift($pageParts); //first item is null so remove it.
		//find the route item ie parent=self
		
		//root directory must be present where parent is itself you can name it what you like
		//and it can have any route  as it is not matched eg could use blank or /
		$query = $this->em->createQuery("SELECT v FROM Routes v WHERE v.parent=v");
		$route=array_shift($query->getResult());
		$display = "default controller";
		$page=null;	
		 //note a "root" route must be set up so top level can be matched.
		 //the second route name can be used to look up site for Gucci
		if(count($pageParts)>0){  	
		    //loop through  where parent=last route id and route matches directory page part
		    while(count($pageParts)>0 && !empty($route)){
		        $routeName=array_shift($pageParts);
		        $route=$this->em->getRepository('Routes')->findOneBy(array('parent'=>$route->getId(), 'route'=>$routeName));
		    }    
		}
		if(!empty($route)){ 
		    //the last route found ie $route must also match page route 
		    $page = $this->em->getRepository('Pages')->findOneBy(array( 'alias'=>$pageAlias,'route'=>$route->getId())); 
		}
		if(!empty($page)){
		    $pageName=$page->getName();
		    $display.= "<br/>Page lookup=$pageAlias pageName=$pageName ";
		    $template=$page->getTemplate();
		    $this->view->content=Wednesday_Renderers_Template::viewRender($template,$page);
		      
		 } else {
		    $this->view->content="Sorry this page is no longer available";
		    //set 404 header
		    $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
		    $this->getResponse()->setHeader('Status','404 File not found');
		 }
		$display.= "<br/>Try example url which has been set up in routes and pages: /test/page2.html";
		$this->log->info(get_class($this).'::indexAction()');
		$this->view->message = $display;
		*/
    }
    
    public function varsAction() {
//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout->disableLayout();
        $translate = $this->getInvokeArg('bootstrap')->getContainer()->get('translate');
        $locale = $this->getRequest()->getParam('locale');
        $translate->setLocale($locale);
        //*
        $jsonObject = (object) array(
                "__ERROR_MESSAGES" => array(
                        "__MANDATORY" => array(
                                "long_description"   => $translate->_("ERROR_MANDATORY_LONG"),
                                "short_description"  => $translate->_("ERROR_MANDATORY_SHORT")
                        ),
                        "__EMAIL" => array(
                                "long_description"   => $translate->_("ERROR_EMAIL_LONG"),
                                "short_description"  => $translate->_("ERROR_EMAIL_SHORT")
                        ),
                        "__MAXIMUM_FOURTY_CHARACTERS" => array(
                                "long_description"   => $translate->_("ERROR_MAXIMUM_LONG"),
                                "short_description"  => $translate->_("ERROR_MAXIMUM_SHORT")
                        ),
                        "__NUMERIC" => array(
                                "long_description"   => $translate->_("ERROR_NUMERIC_LONG"),
                                "short_description"  => $translate->_("ERROR_NUMERIC_SHORT")
                        )
                ),
                "__LINK_TEXT" => array(
                        "__CAROUSEL_NEXT" => array(
                                "text"   => $translate->_("LINK_TEXT_NEXT_TEXT"),
                                "title"  => $translate->_("LINK_TEXT_NEXT_TITLE")
                        ),
                        "__CAROUSEL_PREV" => array(
                                "text"   => $translate->_("LINK_TEXT_PREV_TEXT"),
                                "title"  => $translate->_("LINK_TEXT_PREV_TITLE")
                        ),
                        "__OPEN" => array(
                                "text"   => $translate->_("LINK_TEXT_OPEN_TEXT"),
                                "title"  => $translate->_("LINK_TEXT_OPEN_TITLE")
                        ),
                        "__CLOSE" => array(
                                "text"   => $translate->_("LINK_TEXT_CLOSE_TEXT"),
                                "title"  => $translate->_("LINK_TEXT_CLOSE_TITLE")
                        )
                ),
                "__FOOTER_TEXT" => array(
                        "__FOOTER_SWITCH_TEXT" => array(
                                "text"   => $translate->_("LINK_TEXT_FOOTER_TEXT"),
                                "title"  => $translate->_("LINK_TEXT_FOOTER_TITLE")
                        )
                ),
                "__TAPESTRY_INSTRUCTION_TEXT" => array(
                        "touch"   => $translate->_("TAPESTRY_INSTRUCTION_TOUCH"),
                        "desktop"  => $translate->_("TAPESTRY_INSTRUCTION_DESKTOP")
                )
        );
        //*/
        $this->_helper->json($jsonObject);
        //echo Zend_Json::encode($jsonObject);
    }
    /**
     * lynxAction.
     * /
    public function lynxAction() {
        $this->log->info(get_class($this).'::lynxAction()');
        $jqnc = ZendX_JQuery_View_Helper_JQuery::getJQueryHandler();
        $lynxScript = <<<TEST
{$jqnc}(document).ready(function() {
	{$jqnc}('a.lynx').linkTester();
});        
TEST;
		$this->view->inlineScript()-> appendScript($lynxScript, 'text/javascript');        
		$page = get_class($this).'::lynxAction()<hr /><br />';
		$page .= "<a href='/cmis/index/index' class='lynx action-create'>create</a><br />";
		$page .= "<a href='/cmis/index/index' class='lynx action-read'>read</a><br />";
		$page .= "<a href='/cmis/index/index' class='lynx action-update'>update</a><br />";
		$page .= "<a href='/cmis/index/index' class='lynx action-delete'>delete</a><br />";
        $this->view->message = $page;
	}
	
    /**
     * cacheAction.
     * /
    public function cacheAction() {

        $xslFile = APPLICATION_PATH."/plugins/helpers/default.tmpl.xsl";
        $cacheFile = "cache.SiteHome.20110216.am.xml";
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        //TODO Build Cache Renderer : Xslt Processor PHP 5.
        $xp = new XsltProcessor();
        $xp->registerPHPFunctions();
        $xp->setProfiling(APPLICATION_PATH."/../data/logs/xslt.info.log");
        $xp->setParameter( 'DOCTYPE', 'SYSTEM', '' );
        try {
            // create a DOM document and load the XSL data
            $xslt = new DomDocument();
            $xslt->load($xslFile);
            // create a DOM document and load the XML data
            $xml = new DomDocument();
            $xml->load($cacheFile);
            // import the XSL styelsheet into the XSLT process
            $this->log->info('XSL::'.$xslt->saveXML());
            $xp->importStylesheet($xslt);
            $this->log->info('XSL::');
            // transform the XML into HTML using the XSL file
            if ($this->tmplOutput = $xp->transformToDoc($xml)) {
                //header( "Content-Type: text/html; charset=utf-8;" );
                //header( "Content-Encoding: utf-8" );
                $display = $this->tmplOutput->saveHTML();
            } else {
                throw new Zend_Exception('XSL transformation failed.');
            }
        } catch (Exception $exc) {
            echo "Hmmm".$exc->getTraceAsString();
        }

        $this->view->message = $display;//print_r($this->tmplOutput,true);
        echo $display;
        $this->log->info(get_class($this).'::cacheAction()');
    }
	//*/
}