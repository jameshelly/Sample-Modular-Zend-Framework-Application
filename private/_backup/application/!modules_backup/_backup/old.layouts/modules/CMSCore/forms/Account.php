<?php

class CMSCore_Form_Account extends Zend_Form {

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
			'id'       => 'account_form_uid',
			'class'       => 'form account',
			'onSubmit' => 'validate(this)',
		));
		
		$roleList=array();
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');
		$roleEntities = $em->getRepository('Roles')->findAll();
		foreach($roleEntities as $role) {
			$roleList[$role->getId()] = $role->getName();
		}
		
	
		$this->addElement('text', 'firstname', array(
			'label' => 'First Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('text', 'lastname', array(
			'label' => 'Last Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		$this->addElement('text', 'email', array(
			'label' => 'Email',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		$this->addElement('select', 'role_id', array(
			'label' => 'Role',
			'multioptions'   => $roleList,
			'required' => true
		));
		
		
		$this->addElement('text', 'username', array(
			'label' => 'User Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('password', 'setpass1', array(
			'label' => 'Set Password (leave blank to leave unchanged or set to random)',
			'required' => false,
		    
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('password', 'setpass2', array(
			'label' => 'Set Password (retype to confirm)',
			'required' => false,
			'filters'    => array('StringTrim'),
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