<?php


use DoctrineExtensions\WedVersionable\Versionable;

/**
 * Datasource
 *
 * @Table(name="datasource")
 * @Entity
 */
class Datasource implements Versionable
{
 	/**
     * @var string $id
     *
     * @Column(name="id", type="string", length=24, nullable=false)
     * @Id
     */
    private $id;
	

   /**
     * 
     * @var string $page
     * @ManyToOne(targetEntity="Pages")
     */
    private $page;
    

   /**
     * @var integer $variable;
     * @ManyToOne(targetEntity="Variables")
     */
    private $variable;
    
    /**
     * @var integer $template;
     * @ManyToOne(targetEntity="Templates")
     */
    private $template;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string $type
     *
     * @Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;
    
     /**
     * @var string $resource
     *
     * @Column(name="resource", type="string", length=45, nullable=true)
     */
    private $resource;
    
     /**
     * @var string $action
     *
     * @Column(name="action", type="string", length=45, nullable=true)
     */
    private $action; 
    
     /**
     * @var string $reference
     *
     * @Column(name="reference", type="string", length=45, nullable=true)
     */
    private $reference; 
    
    
      /** 
      * @Column(type="string")
      * @publish_version
      *
      */
    private $publishVersion;
    

    /**
     * @Column(type="integer")
     * @version
     */
    private $version;

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
     * Set variable
     *
     * @param integer $variable
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
    }

    /**
     * Get variable
     *
     * @return integer $variable
     */
    public function getVariable()
    {
        return $this->variable;
    }

   

   
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->Type = $type;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }
    
    
    /**
     * Set resource
     *
     * @param string $resource
     */
    public function setResource($resource)
    {
        $this->Resource = $resource;
    }

    /**
     * Get resource
     *
     * @return string $resource
     */
    public function getResource()
    {
        return $this->resource;
    }
    
    
    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string $action
     */
    public function getAction()
    {
        return $this->action;
    }
    
    
    /**
     * Set reference
     *
     * @param string $reference
     */
    public function setRefernce($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get reference
     *
     * @return string $reference
     */
    public function getReference()
    {
        return $this->reference;
    }
    
    
    
     /**
     * Set page
     *
     * @param Resources $page
     */
    public function setPage(\Pages $page)
    {
        $this->page = $page;
    }
    
    /**
     * get page
     *
     * @param Resources $page
     */
    public function getPage()
    {
       return $this->page;
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
     * get template
     *
     * @param Templates $template
     */
    public function getTemplate()
    {
       return $this->template;
    }
    
   
  
      /**
     * Get PublishVersion
     *
     * @return stirng $publishVersion
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