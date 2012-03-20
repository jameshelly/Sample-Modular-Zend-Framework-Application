<?php

//namespace Application\Module\Controller;

use \Application\Forms,
    \Application\Entities,
    \Application\Forms\Default_Form_Login,
    Opentag\Auth\Adapter\Doctrine,
    Opentag\ControllerInterface as ControllerInterface,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugPlugin,
    Zend_Controller_Action as ControllerAction,
    Zend\Di\Configuration,
    Zend\Di\ServiceLocation,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\LessFilter;
use Assetic\Filter\Yui;

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class IndexController extends ControllerAction implements ControllerInterface {

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * @var \App\Service\RandomQuote
     * @InjectService RandomQuote
     */
    protected $_randomQuote = null;

    /**
     * @var \Opentag\Service\Doctrine
     * @InjectService doctrine
     */
    protected $_doctrine;


    public function init() {
        $this->view->headTitle('title');
//        $this->view->layout()->title;
        $this->view->header = <<<EOHTML
        <div class="hero-unit">
            <h1>Doctrine Integration</h1>
            <p>{$this->view->title}</p>
            <p><a class="btn btn-primary btn-large">Learn more »</a></p>
        </div>
EOHTML;

//    DI Integration
//    GetAsseticsService Assetics
//        $css = new AssetCollection(array(
//            new FileAsset('/path/to/src/styles.less', array(new LessFilter())),
//            new GlobAsset('/path/to/css/*'),
//        ));
//
//        // this will echo CSS compiled by LESS and compressed by YUI
//        echo $css->dump();
    }

    /**
     * This action handles
     *    - Default page
     */
    public function indexAction() {
        $this->view->title= "Sample Zend Modular Application";
        $this->view->message = "Doctrine 2.2.1, Gedmo ";
        $this->view->message .= "<p><a href='" . $this->view->url(array('module' => 'blog', 'controller' => 'index', 'action' => 'index')) . "'>View the Blog</a></p>";
    }

}