<?php
namespace Application\Forms;

use \Doctrine\Common\Collections\ArrayCollection,
    \Gedmo\Timestampable\Timestampable,
    \Zend_Form;

class Default_Form_Login extends \Zend_Form {

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
	    'id'       => 'default_login',
	    'class'    => 'login dialog',
	    'onSubmit' => 'validate(this)',
	));

	$this->addElement(
	    'text', 'username', array(
	    'label' => 'Username',
	    'required' => true,
	    'filters'    => array('StringTrim'),
	));

	$this->addElement('password', 'password', array(
	    'label' => 'Password',
	    'required' => true,
	));

	$this->addElement('submit', 'submit', array(
	    'ignore'   => true,
	    'label'    => 'Login',
	));
    }

    /**
     *
     */
    public function mapUserFormToEntity() {

    }
}