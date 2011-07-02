<?php

use DoctrineExtensions\WedVersionable\Versionable;

/**
 * Pageversions
 *
 * @Table(name="pageversions")
 * @Entity
 */
class Pageversions implements Versionable
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

     /**
     * @Column(type="integer")
     * @version
     */
    private $version;
    
     /**
     * @Column(type="integer")
     * @pageversion
     */
    private $pageVersion;
    
    /**
     * @var string $alias
     *
     * @Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;
    
    /**
     * @var string $build
     *
     * @Column(name="build", type="text")
     */
    private $build;
    
  
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
      * @Column(type="string")
      * @publish_version
      *
      */
    private $publishVersion;
    
    
     /** 
      * @Column(type="string")
      * @work_status
      *
      */
    private $workStatus;
    
    
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
     * Get page
     *
     * @return Pages $page
     */
    public function getPage()
    {
        return $this->page;
    }
    
   /**
     * Set page
     *
     * @param Pages $page
     */
    public function setPage(\Pages $page)
    {
        $this->page = $page;
    }

     /**
     * Set build
     *
     * @param string $build
     */
    public function setBuild($build)
    {
        $this->build = $build;
    }

    /**
     * Get build
     *
     * @return string $build
     */
    public function getBuild()
    {
        return $this->build;
    }
    
    
    /**
     * Set alias
     *
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Get alias
     *
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    

    /**
     * Set savedDate
     *
     * @param datetime $savedDate
     */
    public function setsavedDate($savedDate)
    {
        $this->savedDate = $savedDate;
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
     * @param string $ver
     */
    public function setPublishVersion($ver) {
        $this->publishVersion=$ver;
    }
    
    
   
     /**
     * Get workStatus
     *
     * @return string $workStatus
     */
    public function getWorkStatus() {
        return $this->workStatus;
    }
    
    /**
     * Set workStatus
     *
      * @param string $work
     */
    public function setWorkStatus($work) {
        $this->workStatus=$work;
    }
    
    /**
     * Set pageVersion
     *
     * @param integer $pageVersion
     */
    public function setPageVersion($pageVersion)
    {
        $this->pageVersion = $pageVersion;
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
     * Get pageVersion
     *
     * @return string $pageVersion
     */
    public function getPageVersion() {
        return $this->pageVersion;
    }
  
    
     /**
     * Get version
     *
     * @return string $version
     */
    public function getVersion() {
        return $this->version;
    }
  
    
    
}