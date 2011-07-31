<?php

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */
class CMSCore_IndexController extends Wednesday_Controller_Action {

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
		$content .= "<h2>CMSCore</h2>";
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$content .= "<p>".$auth->getIdentity()." logged in</p>";
		} else {
			$this->_redirect("/login");
		}
		$this->view->message = $content;
		$this->log->info(get_class($this).'::indexAction()');
	}
}