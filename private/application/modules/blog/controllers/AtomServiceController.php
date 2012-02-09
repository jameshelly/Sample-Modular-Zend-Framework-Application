<?php

//namespace Application\Module\Controller;

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

class Blog_AtomServiceController extends ControllerAction implements ControllerI {

    public function indexAction() {

//        $this->_cfg;
//        $this->_log;
//        $this->_entm;
//        $this->_evtm;
//        $this->_conn;
//        #Action body
        #Set layout files to look in Theme folder.
        $this->view->layout()->setLayoutPath(WEB_PATH . 'assets/layouts')
                ->setLayout('bootstrap')
                ->setViewBasePath(WEB_PATH . 'assets/views');

        #Set view files based on template, theme & config settings.
        $this->view->addScriptPath(WEB_PATH . 'assets/views');

        #Detect Ajax Early.
        if ($this->_request->isXmlHttpRequest()) {
            $this->getLayout()->disableLayout();
        }

        $this->view->title = "Index Title";
        $this->view->message = "Index Message";
        $this->view->content = "<p>Index Content</p>";
        $this->view->articles = $this->em->getRepository()->findAll();

    }

}
