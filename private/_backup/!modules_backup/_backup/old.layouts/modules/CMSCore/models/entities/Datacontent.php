<?php

use DoctrineExtensions\WedVersionable\Versionable;


/**
 * Datacontent
 *
 * @Table(name="datacontent")
 * @Entity
 */
class Datacontent implements Versionable
{
    /**
     * @var string $id
     *
     * @Column(name="id", type="string", length=24, nullable=false)
     * @Id
     */
    private $id;


    /**
     * @var string $page
     * @ManyToOne(targetEntity="Pages")
     */
    private $page;
    
     /**
     * @var integer $current
     * @Column(type="integer", nullable=true)
     */
    private $current;
    
    
    /**
     * @var integer $variable;
     * @ManyToOne(targetEntity="Variables")
     */
    private $variable;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

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
     * Set variable
     *
     * @param integer $variable
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
    }
    
     /**
     * Get current
     *
     * @return integer $current
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set current
     *
     * @param integer $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
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