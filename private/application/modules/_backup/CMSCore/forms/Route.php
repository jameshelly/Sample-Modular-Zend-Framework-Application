<?php

class CMSCore_Form_Route extends Zend_Form {

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
			'id'       => 'route_form_uid',
			'class'       => 'form route',
			'onSubmit' => 'validate(this)',
		));
		
		$routes = array( 0 => 'No Parent');
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');

		$rtEntities = $em->getRepository('Routes')->findAll();
		foreach($rtEntities as $route) {
			$routes[$route->getId()] = $route->getName();
		}
		
		
		$this->addElement('select', 'parent_id', array(
			'label' => 'Parent',
			'multioptions'   => $routes,
			'required' => true
		));
		
		$this->addElement('text', 'name', array(
			'label' => 'Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('text', 'route', array(
			'label' => 'Alias',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('textarea', 'description', array(
			'label' => 'Description',
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