<?php
/**
 * RoutesController - The default error controller class
 *
 * @category   CMS Pages URL Parameters
 * @package    CMSCore_AccountsController
 * @subpackage AccountsController
 * @copyright  Copyright (c) 2011 Wednesday London. (http://www.wednesday-london.com)
 * @license    Custom License
 * @author     Martin D Marsh <martinmarsh@sygenius.com>
 * @version		0.1
 */

class CMSCore_TestaclController extends Wednesday_Controller_SectionAbstract implements Wednesday_Controller_SectionInterface
{

    /**
     * This action handles
     *    - listAction
     *    -
     */
    public function listAction() {
    	$this->indexAction();
    }

    /**
     * This action handles
     *    - indexAction
     *    -
     */
    public function indexAction() {
		//$this->view->users = $this->em->getRepository('Users')->findAll();
		//$this->view->header = "Users";    	  
    }
    
    
     /**
     * This action handles
     *    - testAction
     *    -
     */
    
    public function testAction() { 
     	  $form = new CMSCore_Form_Testacl();
        
    	  $return = "";
          $this->view->form = $form;
          
          $status="not Allowed";
          
          if($form->isValid($_POST)) {
          	//$acl=new Wednesday_Application_Resource_WedAcl();
			
            $form->populate($_POST);
            $values = $form->getValues();
          
            $role = $this->em->getRepository('Roles')->find($values['role_id'])->getName();
            
            
            $resourceObj = $this->em->getRepository('Aclresources')->find($values['aclresource_id']);
            $resource=null;
            $type='';
            if(!empty($resourceObj)){
                 $resource=$resourceObj->getName();
                 $type=$resourceObj->getType();
            }
            $privilege=null;
            if(!empty($values['privilegetypes_id']) && $values['privilegetypes_id']>0){
            		$newprivilege = $this->em->getRepository('Privilegetypes')->find($values['privilegetypes_id']);
            		$privilege=$newprivilege->getName();
            }           
           
            
          	if($this->acl->isRoleAllowed($role,$type,$resource,$privilege)){
          	   $status="is Allowed";
          	
          	}
       
          	$return .= "<p>Result: $status";
          	$return .= "</p>";
          	$return .= "<a href=\"".$this->view->url(array('module' => 'CMSCore', 'controller' => 'testacl', 'test' => 'index'))."\">Next</a>";       
    	  	$this->view->messages = $return;
          }
          
    }
    
    
 /**
     * This action handles
     *    - createAction
     *    -
     */
    public function createAction() {

    	
    }

    /**
     * This action handles
     *    - readAction
     *    - viewAction
     */
    public function readAction() {
    	$this->viewAction();
    }

    /**
     * This action handles
     *    - viewAction
     *    -
     */
    public function viewAction() {
    	
    	
    }

    /**
     * This action handles
     *    - updateAction
     *    - editAction
     */
    public function updateAction() {
    	$this->editAction();
    }

    /**
     * This action handles
     *    - editAction
     *    -
     */
    public function editAction() {
    	
    	
    }

    /**
     * This action handles
     *    - deleteAction
     *    -
     */
    public function deleteAction() {
    	
    	
    }
    
    


}