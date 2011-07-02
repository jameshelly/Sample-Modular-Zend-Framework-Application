<?php

class CMSCore_Form_Datacontent extends Zend_Form {

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
			'id'       => 'datacontent_form_uid',
			'class'       => 'form datacontent',
			'onSubmit' => 'validate(this)',
		));
		
		
		$this->addElement('hidden', 'name', array(
		    'id'         => 'contentname_id',
			'filters'    => array('StringTrim'),
		));

		
		$this->addElement('hidden', 'id', array(
		    'id'         => 'contentid_id'
		));
		
		$this->addElement('textarea', 'content', array(
			'label' => 'Content',
		    'id' => 'textarea_id',
		    'rows'  => 30,
		    'cols'  => 120
		));
		
		
		
/*
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

                $this->addElement($createddate);*/

		$this->addElement('submit', 'submit', array(
			'ignore'   => true,
			'label'    => 'Save',
		    'onclick'  => 'return saveContents();'
		));
		
	}
    
}