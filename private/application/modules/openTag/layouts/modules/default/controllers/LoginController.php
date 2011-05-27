<?php

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

class LoginController extends Wednesday_Controller_Action
{

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
    	$loginForm = new Default_Form_Login();
        $request = $this->getRequest();
        $adapter = new Wednesday_Auth_Adapter_Doctrine(
            $this->em,
            'Users',
            'getUsername',
            'getPassword',
            "checkPassword"
        );
        $content ="";
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $loginForm .= "<p>".$auth->getIdentity()." logged in</p>";
            //$this->_redirect("user/profile");
        } else if($request->isPost()) {
            if($loginForm->isValid($request->getPost())) {
                $values = $loginForm->getValues();
                $adapter->setIdentity($values['username']);
                $adapter->setCredential($values['password']);
                $result = $auth->authenticate($adapter);
                if (!$result->isValid()) {
                    $auth->clearIdentity();
                    $loginForm->setDescription('Invalid credentials provided');
                    $content .= 'Invalid credentials provided'.print_r($result,true);
                } else {
                    $loginForm = "<p>".$result->getIdentity()." login succeeded</p>";
                }
            } else {
                $loginForm->setDescription('Please login');
            }
        }
    	$content .= "<div id='login-box' class='login rounded'>".$loginForm."</div>";
        $this->view->title = "Users";
        $this->view->message = $content;
    }
}
