<?php

/*
 * Wednesday London 
 * Copyright 2011 
 */

namespace Doctrine\Wednesday\Form;

use ReflectionException,
    Doctrine\ORM\ORMException,
    Doctrine\ORM\EntityManager,
    Doctrine\DBAL\Platforms,
    Doctrine\ORM\Events,
    Doctrine\DBAL\Types, 
    \ZendX_JQuery,
    \Zend_Form,
    \Zend_Form_Element
    \Zend_Form_Element_Submit,
    \ZendX_JQuery_Form_Element_DatePicker,
    \Zend_Form_SubForm;

/**
 * Description of FormAbstract
 *  This class takes an entity and maps the properties to form elements and then returns a \Zend_Form_SubForm
 * @author james a helly <james@wednesday-london.com>
 *
 * TODO: Gedmo hide lvl, root etc ... 
 * TODO: Custom form helpers include
 */
class FormAbstract {
    
    /**
     * @var EntityManager
     */
    private $em;
    
    protected $log;
    
    protected $entityName;

    public function __construct($name){
        $this->entityName = $name;
        #Get Logger   
        $front = \Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam("bootstrap");
        //$this->log = $bootstrap->getResource('Log');
        $this->log = $bootstrap->getContainer()->get('logger');
        $this->log->debug(get_class($this).'::EntityFormRenderer('.$name.')');
    }

    /**
     * @param EntityManager $$em
     */
    public function setEntityManager(EntityManager $em)
    {   
        $eventManager = $em->getEventManager();
        $open = $em->isOpen()?'open':'closed';
        $this->log->info(get_class($this).'::setEntityManager(<pre>'.$open.'</pre>)');
        $this->em = $em;
    }    
    
    /**
     *
     * @param type $entity
     * @param Zend_Form $form 
     */
    public function populate($entity, $entityName, $form)
    {
        $array = array( $entityName => $entity->toArray());
//        print_r($entity->toArray());
//        die();
//        $form->setDefaults($array);
        $form->populate($array);
    }    
    
    /**
     *
     * @param String $entityName
     * @return array 
     */
    protected function getOptionsForEntity($entityName) {
        //return false;
        if(is_numeric($entityName)||($entityName=='1')) {
            $this->log->info(get_class($this).'::getOptionsForEntity(<pre>'.$entityName.'</pre>)');
            return false;
        }
        $this->log->info(get_class($this).'::getOptionsForEntity(<pre>'.$entityName.'</pre>)');
        $options = array();
        $entities = $this->em->getRepository($entityName)->findAll();
//	echo get_class($this).''.$entityName.'</br>';
        foreach($entities as $entity) {
//	    echo $entity->id." - ".$entity->title;
            $options[$entity->id] = $entity->id." - ".$entity->title;
        }
        return $options;
    }
    
    /**
     *
     * @param type $fieldMapping
     * @return Zend_Form_Element 
     */
    protected function getElementMapping($fldMapping, $isAssociation = false) {
        $this->log->info(get_class($this).'::mapEntityToForm(<pre>'.$fldMapping['fieldName'].'</pre>)');
//        echo "<pre>";
//        var_dump($fldMapping);
//        echo "</pre>";
//        die();
        //$fldMapping = $this->fldMapping;
        $decoratorType = 'text';
        $type = $fldMapping['type'];
        $elementClass = 'Zend_Form_Element_Text';
        $element = null;
        
        if(trim($fldMapping['fieldName'])=='1') {
            $this->log->info(get_class($this).'::mapEntityToForm(<pre>Its ONE!!</pre>)');
        }
        if($isAssociation == true) {
            if(trim($fldMapping['fieldName'])=='1') {
                $this->log->info(get_class($this).'::mapEntityToForm(<pre>Its ONE!!</pre>)');
            }
            $options = $this->getOptionsForEntity($fldMapping['targetEntity']);
	    #TODO make element multi select if it can be.
            $element = new \Zend_Form_Element_Select(array(
                    'name' => strtolower($fldMapping['fieldName']), 
                    'label' => $fldMapping['fieldName'],
		    'multiple' => false,
                    'required' => false
                ));
            $element->addMultiOptions($options);
        } else {
          switch($type) {
                case 'integer':
                case 'string':
//                    $decoratorType = 'formText';
                    $decoratorType = 'text';
                    $elementClass = 'Zend_Form_Element_Text';
                    break;
                case 'text':
//                    $decoratorType = 'formTextarea';
                    $elementClass = 'Zend_Form_Element_Textarea';
                    $decoratorType = 'jquery_ckeditor';
//                    $decoratorType = 'jquery_textarea';
                    break;
                case 'datetime':
                    $decoratorType = 'datepicker';
                    $elementClass = 'ZendX_JQuery_Form_Element_DatePicker';                 
                    //ZendX_JQuery_Form_Element_DatePicker
//                    $decoratorType = 'DatePicker';
//                    break;
//                      $element = new ZendX_JQuery_Form_Element_DatePicker(
//                                    $fldMapping['fieldName'],
//                                array('label' => $fldMapping['fieldName'],'jQueryParams' => array('defaultDate' => '2007/10/10')));
break;
                default:
//                    $decoratorType = 'formText';
                    $decoratorType = 'text';
                    $elementClass = 'Zend_Form_Element_Text';
                    break;
            }
//            //$options = $this->getOptionsForEntity($fldMapping['targetEntity']);
            $this->log->info(get_class($this).'::mapEntityToForm(<p>'.$elementClass.' '.$fldMapping['fieldName'].' '.$decoratorType.' Class}</p>)');            
//            if($element instanceof \ZendX_JQuery_View_Helper_DatePicker){
//                
//            } else {
            $element = new $elementClass(
                array(
                    'name' => strtolower($fldMapping['fieldName']), 
                    'label' => $fldMapping['fieldName'],
                    'class' => $decoratorType,
                    'required' => false,
                    'jQueryParams' => array('defaultDate' => '2007/10/10')
                )
            );
////            $element->addMultiOptions($options);
////                       
////            $element = new Zend_Form_Element(array(
////                        'name' => $fldMapping['fieldName'], 
////                        'label' => $fldMapping['fieldName'],
////                        'required' => true,
////                        'filters'    => array('StringTrim'),
////                    ));
//            }
            
//            $element->helper = $decoratorType;
        }
        return $element;
    }
    
    /**
     *  mapEntityToForm
     *      Main function 
     * @param type $classMetadata
     * @return Zend_Form_SubForm 
     */
    public function mapEntityToForm($classMetadata, $submitLabel = 'Update', $skipFields = array('id')) {
        $this->log->info(get_class($this).'::mapEntityToForm(<pre>'.$classMetadata->name.'</pre>)');
        $form = new Zend_Form_SubForm();
        \ZendX_JQuery::enableForm($form);
        $form->setLegend($classMetadata->name);
        $form->setName(strtolower($classMetadata->name));
        #TODO Add Filter, Order, JQuery Decorators 
        foreach($classMetadata->associationMappings as $fieldName => $fieldMapping) { 
            if(!in_array($fieldName, $skipFields)) {
                $form->addElement($this->getElementMapping($fieldMapping, true));
            }
        }
        
        #TODO Add Filter, Order, JQuery Decorators 
        foreach($classMetadata->fieldMappings as $fieldName => $fieldMapping) { 
            if(!in_array($fieldName, $skipFields)) {
                $form->addElement($this->getElementMapping($fieldMapping));
            }
        }
        
        #TODO Add Filter, Order, JQuery Decorators 
        $submit = new \Zend_Form_Element_Submit('submit', 'submit', array(
                'ignore'   => true,
                'label'    => $submitLabel
        ));
        
        $form->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'div')),
                'Form'
            ));         
        $form->setName('entity-'.$classMetadata->name.'-form');
        
        $form->addElement($submit);
        
        return $form;
    }    
}

