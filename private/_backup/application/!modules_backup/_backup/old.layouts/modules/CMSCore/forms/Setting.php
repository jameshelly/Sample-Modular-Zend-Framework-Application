<?php

class CMSCore_Form_Setting extends Zend_Form {

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
		
		$this->addElement('text', 'param', array(
			'label' => 'Parameter',
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