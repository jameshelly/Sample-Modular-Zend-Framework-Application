<?php
//namespace Application\Module\Controller;

use Opentag\Auth\Adapter\Doctrine,
    \Doctrine\ORM\EntityManager,
    \Zend_Controller_Action,
    \Application\Forms\Login,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;

/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */
class AuthController extends Wednesday_Controller_Action
{

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    public function init() {
        //$this->_loginForm = new Application_Form_Login();
        //$this->view->form = $this->_loginForm;
        // get auth service from bootstrap
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_auth = $bootstrap->getResource('auth');
    }

    public function indexAction() {
        $this->_forward('login');
    }

    /**
     * This action handles
     *    - loginAction
     *    -
     */
    public function loginAction() {
    	$loginForm = new Login();
        $request = $this->getRequest();
        $adapter = new \Opentag\Auth\Adapter\Doctrine(
            $this->em,
            'Application\Entities\Users',
            'username',
            'password',
            "checkPassword"
        );
        $content ="";
        $auth = Zend_Auth::getInstance();//$this->_auth;//
        if ($auth->hasIdentity()) {
            $loginForm .= "<p>".$auth->getIdentity()." logged in</p>";
            //$this->_redirect("user/profile");
        } else if($request->isPost()||$request->isPut()) {
            if($loginForm->isValid($request->getPost())) {
                $values = $loginForm->getValues();
                $adapter->setIdentity($values['username']);
                $adapter->setCredential($values['password']);
                $result = $auth->authenticate($adapter);
                if (!$result->isValid()) {
                    $auth->clearIdentity();
                    $loginForm->setDescription('Invalid credentials provided');
                    $content .= 'Invalid credentials provided <pre>'.print_r($result,true).'</pre>';
                } else {
//                    $ns = new Zend_Session_Namespace('wednesday');
//                    $this->_redirect($ns->authReturn);
//                    exit();
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
	$auth = Zend_Auth::getInstance();//$this->_auth;//
        $auth->clearIdentity();
        $this->view->title = "Users";
        $this->view->message = "Logged out";
    }
    
    public function indexAction() {
        $this->_forward('login');
    }

    public function getAction() {
        $this->_forward('login');
    }

    public function postAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("From postAction('".get_class($this)."') creating the requested Data:".$return);
    }

    public function putAction() {
        $params = $this->_getAllParams();
        $return = "<pre>".print_r($params,true)."</pre>";
        $this->getResponse()->appendBody("From putAction('".get_class($this)."') updating the requested Data:".$return);
        //$this->_forward('login');
        $loginForm = $this->_loginForm;//new Api_Form_Login();
        $request = $this->getRequest();
        $adapter = new Wednesday_Auth_Adapter_Doctrine(
            $this->em,
            'Users',
            'getUsername',
            'getPassword',
            "checkPassword"
        );
        $content ="";
        $auth = Zend_Auth::getInstance();//$this->_auth;//
        if ($auth->hasIdentity()) {
            $loginForm .= "<p>".$auth->getIdentity()." logged in</p>";
            //$this->_redirect("user/profile");
        } else {
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
        $this->getResponse()->appendBody("From '".get_class($this)."' ".$content);
    }

    public function deleteAction() {
    	$this->_forward('logout');
    }

/* EOF Class */
}
