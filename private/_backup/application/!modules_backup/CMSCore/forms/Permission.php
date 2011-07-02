<?php

class CMSCore_Form_Permission extends Zend_Form {

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
			'id'       => 'permission_form_uid',
			'class'       => 'form permission',
			'onSubmit' => 'validate(this)',
		));
		
		$actions = array();
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');
		$actEntities = $em->getRepository('Actions')->findAll();
		foreach($actEntities as $action) {
			$actions[$action->getId()] = $action->getName();
		}
		
		$this->addElement('select', 'action_id', array(
			'label' => 'Action',
			'multioptions'   => $actions,
			'required' => true
		));
		
		$this->addElement('select', 'allowed', array(
			'label' => 'Allow',
			'multioptions'   => array(
                            '1' => 'Yes',
                            '0' => 'No',
                            ),
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