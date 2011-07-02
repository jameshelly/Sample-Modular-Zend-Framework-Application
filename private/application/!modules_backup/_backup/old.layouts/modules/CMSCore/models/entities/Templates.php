<?php


use DoctrineExtensions\WedVersionable\Versionable;

/**
 * Templates
 *
 * @Table(name="templates")
 * @Entity
 */
class Templates implements Versionable
{
    /**
     * @var string $id
     *
     * @Column(name="id", type="string", length=24, nullable=false)
     * @Id
     */
    private $id;


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
     * @var string $content
     *
     * @Column(name="content", type="text", nullable=true)
     */
    private $content;
    
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
     * @var datetime $timeStamp
     *
     * @Column(name="timeStamp", type="datetime", nullable=true)
     */
    private $timeStamp;
    
       
    /**
     *  @Column(name="versioned_id", length=24, type="string")
     */
    private $versionedId;

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
        $this->type = $type;
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
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
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