<?php

class CMSCore_Form_Versionablecontent extends Zend_Form {

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
			'id'       => 'versionablecontent_form_uid',
			'class'    => 'form versionablecontent',
			'onSubmit' => 'validate(this)',
		));
		
	
		$this->addElement('text', 'title', array(
			'label' => 'Title',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
	
		$this->addElement('text', 'content', array(
			'label' => 'Content',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		$this->addElement('submit', 'submit', array(
			'ignore'   => true,
			'label'    => 'update',
		));
		
		
	}
    
}