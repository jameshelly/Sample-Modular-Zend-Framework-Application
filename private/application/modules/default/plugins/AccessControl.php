<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessControl
 *
 * @author mrhelly
 */
class Default_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $resource = $request->getControllerName();
        $privilage = $request->getActionName();

        $auth = Zend_Auth::getInstance();
        $storageObj = $auth->getStorage()->read();
        if(isset($storageObj->usersID)) {
            $rolename = $storageObj->usersRole;
        } else {
            $rolename = 'guest';
        }
        $acl = Zend_Registry::get('Zend_Acl');

        if(!$acl->isAllowed($rolename, $resource, $privilage)) {
            $request->setControllerName('Error');
            $request->setActionName('denied');
        }
    }
}
?>
