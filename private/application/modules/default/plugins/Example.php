<?php
/**
 * Description of Example
 *
 * @author mrhelly
 */
class Default_Plugin_Example extends Zend_Controller_Plugin_Abstract
{
    private $layout;
    private $log;

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->layout = $this->getLayout();
        $this->log = $this->getLog();
        $this->log->info(get_class($this)." routeStartup() called");
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $this->log->info(get_class($this)." routeShutdown() called");
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->log->info(get_class($this)." dispatchLoopStartup() called");
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->log->info(get_class($this)." preDispatch() called");
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $header = "<h1>"."ZF1 Doc2 Demo"."</h1>";
        $footer = "<p>&copy; James Helly 2011.</p>";
        $this->layout->assign('header', $header);
        $this->layout->assign('footer', $footer);
        $this->log->info(get_class($this)." postDispatch() called");
    }

    public function dispatchLoopShutdown()
    {
        $this->log->info(get_class($this)." dispatchLoopShutdown() called");
    }

    public function getLog()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $log = $bootstrap->getContainer()->get('logger');//->getResource('Log');
        return $log;
    }

//    public function getView()
//    {
//        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
//        if (null === $viewRenderer->view) {
//            $viewRenderer->initView();
//        }
//        $view = $viewRenderer->view;
//        return $view;
//    }

    public function getLayout()
    {
        $layout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
        return $layout;
    }
}
