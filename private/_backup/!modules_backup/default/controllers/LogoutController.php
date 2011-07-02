<?php

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

class LogoutController extends Wednesday_Controller_Action
{

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
        $this->view->title = "Users";
        $this->view->message = "Logged out";
    }

}
