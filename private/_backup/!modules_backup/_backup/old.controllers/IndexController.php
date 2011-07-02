<?php

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class Cmis_IndexController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
	public function indexAction() {
        $pathPrefix = $this->getRequest()->getPathInfo();
        $tmp = explode('/',trim($pathPrefix,'/'));
        $pathRoot = $tmp[0];
		$content  = "";
		$content .= "<h2>CMIS</h2>";
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$content .= "<p>".$auth->getIdentity()." logged in</p>";
		} else {
			//$this->_redirect("/login");
		}
		$this->view->message = $content;
		$this->log->info(get_class($this).'::indexAction()');
	}

    /**
     * This action handles
     *    - Application
     *    -
     */
    public function getAction()
    {
        // list one user...
        // GET to /users/1095
        $id = $this->_getParam('id'); //contains 1095
        $this->view->message = $id;
    }
    
    /**
     * This action handles
     *    - Application
     *    -
     */
    public function postAction()
    {
        // create new user...
        // POST to /users
        $id = $this->_getParam('id'); //contains 1095
        $this->view->message = $id;        
    }
    
    /**
     * This action handles
     *    - Application
     *    -
     */
    public function putAction()
    {
        // update existing user...
        // POST to /users/1095?_method=PUT
        $id = $this->_getParam('id'); //contains 1095
        $this->view->message = $id;
        
    }
    
    /**
     * This action handles
     *    - Application
     *    -
     */    
    public function deleteAction()
    {
        // delete existing user...
        // POST to /users/1095?_method=DELETE
        $id = $this->_getParam('id'); //contains 1095
        $this->view->message = $id;        
    }
}