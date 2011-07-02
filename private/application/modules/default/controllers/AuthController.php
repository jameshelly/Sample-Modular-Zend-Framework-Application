<?php

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

class AuthController extends Zend_Controller_Action
{

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    public function init()
    {
        //$this->_loginForm = new Application_Form_Login();
        //$this->view->form = $this->_loginForm;
        
        // get auth service from bootstrap
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_auth = $bootstrap->getResource('auth');
    }

    public function indexAction()
    {
        $this->_forward('login');
    }

    /**
     * This action handles
     *    - loginAction
     *    -
     */
    public function loginAction() {
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
    
    /**
     * This action handles
     *    - logoutAction
     *    -
     */
    public function logoutAction() {
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
        $this->view->title = "Users";
        $this->view->message = "Logged out";
    }
}
