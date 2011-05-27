<?php

/**
 * IndexController - The default controller
 *
 * @author
 * @version
 */

class AdminController extends Wednesday_Controller_Action {

    /**
     * This action handles
     *    - Application
     *    -
     */
	public function indexAction() {

            $display = get_class($this)." Index";

            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                    $display .= "<p>".$auth->getIdentity()." logged in</p>";
            } else {
                    $this->_redirect("/login");
            }

            $this->view->message = $display;
            $this->log->info(get_class($this).'::indexAction()');
	}
}
