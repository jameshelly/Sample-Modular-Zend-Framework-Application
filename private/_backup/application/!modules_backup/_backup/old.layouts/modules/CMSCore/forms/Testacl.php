<?php

class CMSCore_Form_TestAcl extends Zend_Form {

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
			'id'       => 'testacl_form_uid',
			'class'       => 'form testacl',
			'onSubmit' => 'validate(this)',
		));
		
		$roleList=array();
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');
		$roleEntities = $em->getRepository('Roles')->findAll();
		foreach($roleEntities as $role) {
			$roleList[$role->getId()] = $role->getName();
		}
		
	    $aclResources = array( Null => 'Root');
		$aclEntities = $em->getRepository('Aclresources')->findAll();
		foreach($aclEntities as $aclRes) {
			$aclResources[$aclRes->getId()] =$aclRes->getType().':'.$aclRes->getName();
		}
		
		
		$privilegeTypeList=array();
	    $privilegeTypeObjs = $em->getRepository('PrivilegeTypes')->findAll();
		foreach( $privilegeTypeObjs as  $aPrivilegeTypeObj) {
			$privilegeTypeList[$aPrivilegeTypeObj->getId()] =$aPrivilegeTypeObj->getName();
		}
		
	
		
		
		
		$this->addElement('select', 'role_id', array(
			'label' => 'Role',
			'multioptions'   => $roleList,
			'required' => true
		));
		
		$this->addElement('select', 'aclresource_id', array(
			'label' => 'Resource',
			'multioptions'   => $aclResources,
			'required' => false
		));
	
		$this->addElement('select', 'privilegetypes_id', array(
			'label' => 'Privilege',
			'multioptions'   => $privilegeTypeList,
			'required' => true
		));
		
		
		$this->addElement('submit', 'submit', array(
			'ignore'   => true,
			'label'    => 'Lookup',
		));
		
		
	}
    
}