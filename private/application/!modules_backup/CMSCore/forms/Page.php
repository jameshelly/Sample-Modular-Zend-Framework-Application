<?php

class CMSCore_Form_Page extends Zend_Form {

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
			'id'       => 'page_form_uid',
			'class'       => 'form page',
			'onSubmit' => 'validate(this)',
		));
		
		$templates= array();
		$routes = array( );
        $pages = array( 0 => 'No Parent');
		$em = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getContainer()->get('entity.manager');

		$rtEntities = $em->getRepository('Routes')->findAll();
		foreach($rtEntities as $route) {
                    $routes[$route->getId()] = $route->getName();
		}

		$pgEntities = $em->getRepository('Pages')->findAll();
		foreach($pgEntities as $page) {
                    $pages[$page->getId()] = $page->getName();
		}
		
	    $tpEntities = $em->getRepository('Templates')->findAll();
		foreach($tpEntities as $template) {
                    $templates[$template->getId()] = $template->getName();
		}
		
		$this->addElement('text', 'name', array(
			'label' => 'Name',
			'required' => true,
			'filters'    => array('StringTrim'),
		));

		$this->addElement('text', 'alias', array(
			'label' => 'Alias',
			'required' => true,
			'filters'    => array('StringTrim'),
		));

		$this->addElement('select', 'parent_id', array(
			'label' => 'Parent',
			'multioptions'   => $pages,
			'required' => true
		));

		$this->addElement('select', 'route_id', array(
			'label' => 'Route',
			'multioptions'   => $routes,
			'required' => true
		));
		
		$this->addElement('select', 'template_id', array(
			'label' => 'Template',
			'multioptions'   => $templates,
			'required' => true
		));
		

		$this->addElement('text', 'skin', array(
			'label' => 'Skin',
			'required' => true,
			'filters'    => array('StringTrim'),
		));
		
		$this->addElement('textarea', 'options', array(
			'label' => 'Options',
		));

                $createddate = new ZendX_JQuery_Form_Element_DatePicker('created');
                $createddate->setLabel('Created')
                    ->setJQueryParam('dateFormat', 'D M yy')
                    ->setJQueryParam('changeYear', 'true')
                    ->setJqueryParam('changeMonth', 'true')
                    ->setJqueryParam('regional', 'en')
                    ->setJqueryParam('yearRange', "2000:2200")
                    ->setDescription('D M yy')
                    ->addValidator(new Zend_Validate_Date(array(
                    'format' => 'd m Y',
                )))->setRequired(true);

                $this->addElement($createddate);

//		$this->addElement('date', 'created', array(
//			'label' => 'Created',
//			'required' => true,
//			'filters'    => array('StringTrim'),
//		));

                $updateddate = new ZendX_JQuery_Form_Element_DatePicker('updated');
                $updateddate->setLabel('Updated')
                    ->setJQueryParam('dateFormat', 'D M yy')
                    ->setJQueryParam('changeYear', 'true')
                    ->setJqueryParam('changeMonth', 'true')
                    ->setJqueryParam('regional', 'en')
                    ->setJqueryParam('yearRange', "2000:2200")
                    ->setDescription('D M yy')
                    ->addValidator(new Zend_Validate_Date(array(
                    'format' => 'd m Y',
                )))->setRequired(true);

                $this->addElement($updateddate);

//                $this->addElement('date', 'updated', array(
//			'label' => 'Updated',
//			'required' => true,
//			'filters'    => array('StringTrim'),
//		));

                $publishstartdate = new ZendX_JQuery_Form_Element_DatePicker('publishstartdate');
                $publishstartdate->setLabel('Publish Start')
                    ->setJQueryParam('dateFormat', 'D M yy')
                    ->setJQueryParam('changeYear', 'true')
                    ->setJqueryParam('changeMonth', 'true')
                    ->setJqueryParam('regional', 'en')
                    ->setJqueryParam('yearRange', "2000:2200")
                    ->setDescription('D M yy')
                    ->addValidator(new Zend_Validate_Date(array(
                    'format' => 'd m Y',
                )))->setRequired(true);

                $this->addElement($publishstartdate);

//		$this->addElement('date', 'publishstartdate', array(
//			'label' => 'Publish Start',
//			'required' => true,
//			'filters'    => array('StringTrim'),
//		));

                $publishenddate = new ZendX_JQuery_Form_Element_DatePicker('publishenddate');
                $publishenddate->setLabel('Publish End')
                    ->setJQueryParam('dateFormat', 'D M yy')
                    ->setJQueryParam('changeYear', 'true')
                    ->setJqueryParam('changeMonth', 'true')
                    ->setJqueryParam('regional', 'en')
                    ->setJqueryParam('yearRange', "2000:2200")
                    ->setDescription('D M yy')
                    ->addValidator(new Zend_Validate_Date(array(
                    'format' => 'd m Y',
                )))->setRequired(true);

                $this->addElement($publishenddate);

//		$this->addElement('date', 'publishenddate', array(
//			'label' => 'Publish End',
//			'required' => true,
//			'filters'    => array('StringTrim'),
//		));

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