<?php

use DoctrineExtensions\WedVersionable\Versionable;

/**
 * Templatevariableassign
 *
 * @Table(name="template_variable_assign")
 * @Entity
 */
class Templatevariableassign implements Versionable
{
   /**
     * @var string $id
     *
     * @Column(name="id", type="string", length=24, nullable=false)
     * @Id
     */
    private $id;
    
    /**
     * @var string $template
     *
     * @ManyToOne(targetEntity="Templates")
     */
    private $template;

    /**
     * @var string $variable
     *
     * @ManyToOne(targetEntity="Variables")
     */
    private $variable;

    /**
     * @Column(type="integer")
     * @variableorder
     *
     */
    private $variableorder;

  
    /**
     * @Column(type="integer")
     * @version
     */
    private $version;

    
   /** 
      * @Column(type="string")
      * @publish_version
      *
      */
    private $publishVersion;
    
      
    /**
     *  @Column(name="versioned_id", length=24, type="string")
     */
    private $versionedId;
    
     /**
     * @var datetime $timeStamp
     *
     * @Column(name="timeStamp", type="datetime", nullable=true)
     */
    private $timeStamp;

     /**
     * Set id
     *
     * @param string $id
     */
     public function setId($id)
     {
        return $this->id=$id;
     }
     
    
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order
     *
     * @param integer $order
     */
    public function setVariableOrder($order)
    {
        $this->variableorder = $order;
    }

    /**
     * Get order
     *
     * @return integer $order
     */
    public function getVariableOrder()
    {
        return $this->variableorder;
    }
    
     /**
     * Set template
     *
     * @param Templates $template
     */
    public function setTemplate(\Templates $template)
    {
        $this->template = $template;
    }

    /**
     * Get template
     *
     * @return Templates $template
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
     /**
     * Set variable
     *
     * @param Variable $variable
     */
    public function setVariable(\Variables $variable)
    {
        $this->variable = $variable;
    }

    /**
     * Get variable
     *
     * @return Variable $variable
     */
    public function getVariable()
    {
        return $this->variable;
    }
      
    
     /**
     * Get PublishVersion
     *
     * @return string $publishVersion
     */
    public function getPublishVersion() {
        return $this->publishVersion;
    }
    
    /**
     * Set PublishVersion
     *
     * @return string $publishVersion
     */
    public function setPublishVersion($ver) {
        $this->publishVersion=$ver;
    }
    
     /**
     * Get version
     *
     * @return string $version
     */
    public function getVersion() {
        return $this->version;
    }
    
    /**
     * Get versioned_id
     *
     * @return string $versioned_id
     */
    public function getVersionedId() {
        return $this->versionedId;
    }
  
    /**
     * Get timeStamp
     *
     * @return datetime $timeStamp
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }
    
}