<?php

/**
 * ErrorController - The default error controller class
 * 
 * @author
 * @version 
 */

class ErrorController extends Zend_Controller_Action {

    /**
     * This action handles  
     *    - Application errors
     *    - Errors in the controller chain arising from missing 
     *      controller classes and/or action methods
     */
    public function errorAction() {
    	
        $errors = $this->_getParam('error_handler');
        $this->view->layout()->page_title = "Error";
        // Log exception, if logger available
                
        switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
		        $this->getResponse()->setHttpResponseCode(404);
                $this->view->title = 'HTTP/1.1 404 Not Found';
                $this->view->message = 'Page not Found';
                //$this->log->err('::404 Not Found ['.$_SERVER['REQUEST_URI'].']');//['URI']
		        if ($log = $this->getLog()) {
		            $log->err('::404 Not Found ['.$_SERVER['REQUEST_URI'].']');
		        }                
                break;
            default:
                // application error; display error page, but don't change status code
		        $this->getResponse()->setHttpResponseCode(500);
                $this->view->title = 'Application Error';
                $this->view->message = 'Application Error';
                //$this->log->crit($errors->exception);
		        if ($log = $this->getLog()) {
		            $log->crit($this->view->message, $errors->exception);
		        }                
                break;
        }
        
		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
		  $this->view->exception = $errors->exception;
		}
		$this->view->request = $errors->request;
		
	    if ($log = $this->getLog()) {
	        $log->info(get_class($this).'::errorAction()');
	    }                
		
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
}
