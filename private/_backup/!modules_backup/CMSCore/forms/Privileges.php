<?php

class CMSCore_Form_Privileges extends Zend_Form {

	//TODO Setup Cache Vars?
	public function loadDefaultDecorators() {
		$this->setDecorators(array(
			'FormElements',
			'Fieldset',
			'Form'
		));
	}
	
	public function init() {
		
		$this->setMethod('post'); 
		$this->addAttribs(array(
			'id'       => 'privilege_form_uid',
			'class'       => 'form privileges',
			'onSubmit' => 'validate(this)',
		));
		
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');
		
	    $aclResources = array( Null => 'Default');
		$aclEntities = $em->getRepository('Aclresources')->findAll();
		foreach($aclEntities as $aclRes) {
			$aclResources[$aclRes->getId()] =$aclRes->getType().':'.$aclRes->getName();
		}
		$privilegeTypeList=array();
	    $privilegeTypeObjs = $em->getRepository('PrivilegeTypes')->findAll();
		foreach( $privilegeTypeObjs as  $aPrivilegeTypeObj) {
			$privilegeTypeList[$aPrivilegeTypeObj->getId()] =$aPrivilegeTypeObj->getName();
		}
		
		$roleList=array();
	    $roleEntities = $em->getRepository('Roles')->findAll();
		foreach($roleEntities as $role) {
			$roleList[$role->getId()] = $role->getName();
		}
	
		$this->addElement('select', 'privilegetypes_id', array(
			'label' => 'Privilege',
			'multioptions'   => $privilegeTypeList,
			'required' => true
		));
	
		
		$this->addElement('checkbox', 'allowed', array(
			'label' => 'Allowed',
			
		));
		

		
		$this->addElement('select', 'aclresource_id', array(
			'label' => 'AclResource',
			'multioptions'   => $aclResources,
			'required' => true
		));
	
			
		$this->addElement('select', 'role_id', array(
			'label' => 'Role',
			'multioptions'   => $roleList,
			'required' => true
		));	
		
		$this->addElement('submit', 'submit', array(
			'ignore'   => true,
			'label'    => 'Save',
		));
		
		$this->addElement('reset', 'reset', array(
			'ignore'   => true,
			'label'    => 'Cancel',
		));
	}
    
}