<?php

class CMSCore_Form_Action extends Zend_Form {

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
			'id'       => 'action_form_uid',
			'class'       => 'form action',
			'onSubmit' => 'validate(this)',
		));
		
		$this->addElement('text', 'name', array(
			'label' => 'Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('text', 'description', array(
			'label' => 'Description',
			'required' => true,
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