<?php

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

class UserController extends Wednesday_Controller_Action
{

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		$auth = Zend_Auth::getInstance();
    	$content = "<p>".$auth->getIdentity()." logged in</p>";
        $this->view->title = "Users";
        $this->view->message = $content;
    }
    
    public function profileAction() {
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$loginForm = "<p>".$auth->getIdentity()." logged in</p>";
			//$this->_redirect("user/profile");
		} else {
			$this->_redirect("/login");
		}
		$content .= "<p>".$auth->getIdentity()." logged in</p>";
        $this->view->title = "Users";
        $this->view->message = $content;
    }
}
