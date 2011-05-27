<?php

class CMSCore_Form_Site extends Zend_Form {

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
			'id'       => 'login_form_uid',
			'class'       => 'form login',
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