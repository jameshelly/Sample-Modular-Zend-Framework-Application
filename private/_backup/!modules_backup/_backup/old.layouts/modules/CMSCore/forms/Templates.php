<?php

class CMSCore_Form_Templates extends Zend_Form {

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
			'id'       => 'template_form_uid',
			'class'       => 'form page',
			'onSubmit' => 'validate(this)',
		));
		
		
		
		$this->addElement('text', 'name', array(
			'label' => 'Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
	
		
		$this->addElement('select', 'type', array(
			'label' => 'type',
		    'multioptions' => array('general'=>'general content','structure'=>'page structure','aggregation'=>'content aggregator /sub template','news'=>'news'),
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		
		
		$this->addElement('textarea', 'content', array(
			'label' => 'Content',
		    'id' => 'textarea_id',
		    'rows'  => 30,
		    'cols'  => 120
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