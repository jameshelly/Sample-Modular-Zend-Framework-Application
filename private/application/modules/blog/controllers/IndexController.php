<?php
//namespace Application\Module\Controller;

use \Application\Forms,
    \Application\Entities,
    Opentag\ControllerInterface as ControllerI,
//    Application\Forms,
//    Application\Entities,
    Opentag\Service\Doctrine,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugPlugin,
    Zend_Controller_Action as ControllerAction,
    Zend\Di\Configuration,
    Zend\Di\ServiceLocation,
    \Zend_Auth_Result;

class Blog_IndexController extends ControllerAction
{
    const ENTITY_NAME = "Application\Entities\Articles";

    public function init()
    {
    	#Get Doctrine Entity Manager
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->em = $bootstrap->getContainer()->get('entity.manager');
        //$this->view->placeholder('doctrine')->exchangeArray(array('em'=>$this->em));
        $this->view->partialLoop()->setObjectKey('entity');
        $this->view->headTitle('blog');
    }

    public function indexAction()
    {
        // action body
        $this->view->title = "Blog Title";
        $this->view->message = "Blog Message";
        $this->view->content = "<p>Featured Blog Content</p>";
        $this->view->articles = $this->em->getRepository(self::ENTITY_NAME)->findAll();
    }

}





