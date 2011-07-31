<?php

class CMSCore_Form_Variables extends Zend_Form {

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
			'id'       => 'variable_form_uid',
			'class'       => 'form page',
			'onSubmit' => 'validate(this)',
		));
		
		$templateEntityList = array( );
       
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');

		
	
		
		$this->addElement('text', 'name', array(
			'label' => 'Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));

		$this->addElement('select', 'type', array(
			'label' => 'type',
		    'multioptions' => array('undefined'=>'undefined: not set','pagelongtext'=>'pagelongtext: page content','globallongtext'=>'globallongtext: shared page content','aggregate'=>'Aggregate content container'),
			'required' => true,
			'filters'    => array('StringTrim'),
		));

		

		$this->addElement('text', 'filter', array(
			'label' => 'filter',
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