<?php

class CMSCore_Form_Aclresources extends Zend_Form {

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
			'id'       => 'aclresource_form_uid',
			'class'       => 'form aclresource',
			'onSubmit' => 'validate(this)',
		));
		
		
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');
		
		
	    $aclResources = array( Null => 'Root');
		$aclEntities = $em->getRepository('Aclresources')->findAll();
		foreach($aclEntities as $aclRes) {
			$aclResources[$aclRes->getId()] =$aclRes->getType().':'.$aclRes->getName();
		}
		
	
		$this->addElement('text', 'type', array(
			'label' => 'Type',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('text', 'name', array(
			'label' => 'Resource Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		

		
		
		$this->addElement('select', 'parent_id', array(
			'label' => 'Parent',
			'multioptions'   => $aclResources,
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