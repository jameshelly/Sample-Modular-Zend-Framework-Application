<?php

class CMSCore_Form_Roles extends Zend_Form {

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
			'id'       => 'roles_form_uid',
			'class'       => 'form role',
			'onSubmit' => 'validate(this)',
		));
		
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');

		$rtEntities = $em->getRepository('Roles')->findAll();
		foreach($rtEntities as $role) {
			$roles[$role->getId()] = $role->getName();
		}
		
	
		$this->addElement('text', 'name', array(
			'label' => 'Role Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		$this->addElement('textarea', 'description', array(
			'label' => 'Description',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		$this->addElement('select', 'parent_id', array(
			'label' => 'Parent',
			'multioptions'   => $roles,
			'required' => false
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