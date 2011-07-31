<?php
namespace Doctrine\Wednesday\Form;

use ReflectionException,
    Doctrine\ORM\ORMException,
    Doctrine\ORM\EntityManager,
    Doctrine\DBAL\Platforms,
    Doctrine\ORM\Events,
    Doctrine\DBAL\Types, 
    \Zend_Form,
    \Zend_Form_Element,
    \Zend_Form_SubForm,
    \Zend_Controller_Front;

/**
 * Description of EntityFormRenderer
 * Wednesday London 
 * Copyright 2011 
 * @author mrhelly
 */
class EntityFormRenderer extends Zend_Form_SubForm {
    //put your code here
    const _RESTRICTED_PROPERTIES = 'id';
    
    protected $entityName;
    
    protected $log;

    public function __construct($name){
        $this->entityName = $name;
        #Get Logger   
        $front = \Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam("bootstrap");
        //$this->log = $bootstrap->getResource('Log');
        $this->log = $bootstrap->getContainer()->get('logger');
        $this->log->debug(get_class($this).'::EntityFormRenderer('.$name.')');
        //$this->log->debug(get_class($this).'::EntityFormRenderer()');
    }

    public function getFormRenderer($em) {
        $entityName = $this->entityName;
        $mapper = new FormAbstract($this->entityName);
        $mapper->setEntityManager($em);
        $classMetadata = $em->getMetadataFactory()->getMetadataFor($this->entityName);
        $open = $em->isOpen()?'open':'closed';
        $this->log->info(get_class($this).'::getFormRenderer('.$open.' '.$this->entityName.')');
        return $mapper->mapEntityToForm($classMetadata);
    }

    /**
     * Load the default decorators
     *
     * @return Zend_Form_SubForm
     */
    public function loadDefaultDecorators()
    {
        $this->log->info(get_class($this).'::loadDefaultDecorators()');
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('HtmlTag', array('tag' => 'div'))
                 ->addDecorator('Fieldset');
        }
        return $this;
    }    
}

