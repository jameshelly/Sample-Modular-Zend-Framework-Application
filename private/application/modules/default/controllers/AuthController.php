<?php
//namespace Application\Module\Controller;

use \Application\Forms,
    \Application\Entities,
    \Application\Forms\Default_Form_Login,
    Opentag\Auth\Adapter\Doctrine,
    Opentag\ControllerInterface as ControllerI,
    Opentag_ZFDebug_Plugin_Doctrine as ZFDebugPlugin,
    Zend_Controller_Action as ControllerAction,
    Zend\Di\Configuration,
    Zend\Di\ServiceLocation,
    \Zend_Auth_Adapter_Interface,
    \Zend_Auth_Result;
/**
 * ErrorController - The default error controller class
 *
 * @author
 * @version
 */

class AuthController extends ControllerAction
{

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    public function init() {
        $this->_loginForm = new Default_Form_Login();
        //$this->view->form = $this->_loginForm;
        // get auth service from bootstrap
    	#Get bootstrap object.
        $bootstrap = $this->getInvokeArg('bootstrap');
    	#Get Doctrine Entity Manager
        $this->em = $bootstrap->getContainer()->get('entity.manager');
        #Get Zend Auth.
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction() {
        $auth = $this->auth;//Zend_Auth::getInstance();//
        if ($auth->hasIdentity()) {
            $this->view->title = "Users";
            $this->view->message = "<p>".$auth->getIdentity()." logged in</p>";;
        } else {
            $this->_forward('login');
        }
    }

    /**
     * This action handles
     *    - loginAction
     *    -
     */
    public function loginAction() {
    	$loginForm = $this->_loginForm;
        $request = $this->getRequest();
        $adapter = new \Opentag\Auth\Adapter\Doctrine(
            $this->em,
            'Application\Entities\Users',
            'username',
            'password',
            "checkPassword"
        );
        $content ="";
        $auth = $this->auth;
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
	$auth = $this->auth;
        $auth->clearIdentity();
        $this->view->title = "Users";
        $this->view->message = "Logged out";
    }

/* EOF Class */
}
