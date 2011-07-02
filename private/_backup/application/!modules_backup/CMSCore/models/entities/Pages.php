<?php

use DoctrineExtensions\WedVersionable\Versionable;

/**
 * Pages
 *
 * @gedmo:Tree(type="nested")
 * @Table(name="pages")
 * @Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Pages implements Versionable
{

    /**
     * @var string $id
     *
     * @Id @Column(name="id", type="string", length=24, nullable=false)
     */
    private $id;

    /**
     * @var string $name
     * @Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string $skin
     *
     * @Column(name="skin", type="string", length=128, nullable=false)
     */
    private $skin;

     /**
     * @Column(type="integer")
     * @version
     */
    private $version;
    
    /**
     * @var string $alias
     *
     * @Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var text $options
     *
     * @Column(name="options", type="text", nullable=true)
     */
    private $options;
    
   /**
     * @var template
     *
     * @ManyToOne(targetEntity="Templates")
     * @JoinColumns({
     *   @JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $template;

    /**
     * @var datetime $publishStartDate
     *
     * @Column(name="publish_start_date", type="datetime", nullable=true)
     */
    private $publishStartDate;

    /**
     * @var datetime $publishEndDate
     *
     * @Column(name="publish_end_date", type="datetime", nullable=true)
     */
    private $publishEndDate;

    /**
     * @var datetime $createdDate
     *
     * @Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var datetime $updatedDate
     *
     * @Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;
    
     /**
     * @var datetime $timeStamp
     *
     * @Column(name="timeStamp", type="datetime", nullable=true)
     */
    private $timeStamp;
    
    /**
     * @var Routes
     *
     * @ManyToOne(targetEntity="Routes")
     * @JoinColumns({
     *   @JoinColumn(name="route_id", referencedColumnName="id")
     * })
     */
    private $route;
    
     /** 
      * @Column(type="string")
      * @publish_version
      */
    private $publishVersion;
     
    /**
     *  @Column(name="versioned_id", length=24, type="string")
     */
    private $versionedId;
   
    /**
     * @gedmo:TreeLeft
     * @Column(name="lft", type="integer")
     */
    private $lft;
     
    /**
     * @gedmo:TreeLevel
     * @Column(name="lvl", type="integer")
     */
    private $lvl;
     
    /**
     * @gedmo:TreeRight
     * @Column(name="rgt", type="integer")
     */
    private $rgt;
     
    /**
     * @gedmo:TreeRoot
     * @Column(name="root", type="integer")
     */
    private $root;
     
    /**
     * @gedmo:TreeParent
     * @ManyToOne(targetEntity="Pages", inversedBy="children")
     */
    private $parent;
     
    /**
     * @OneToMany(targetEntity="Pages", mappedBy="parent")
     * @OrderBy({"lft" = "ASC"})
     */
    private $children;

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
     * @return string $id
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
     * Set skin
     *
     * @param string $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }

    /**
     * Get skin
     *
     * @return string $skin
     */
    public function getSkin()
    {
        return $this->skin;
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
     * Set options
     *
     * @param text $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Get options
     *
     * @return text $options
     */
    public function getOptions()
    {
        return $this->options;
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
     * Set publishStartDate
     *
     * @param datetime $publishStartDate
     */
    public function setPublishStartDate($publishStartDate)
    {
        $this->publishStartDate = $publishStartDate;
    }

    /**
     * Get publishStartDate
     *
     * @return datetime $publishStartDate
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * Set publishEndDate
     *
     * @param datetime $publishEndDate
     */
    public function setPublishEndDate($publishEndDate)
    {
        $this->publishEndDate = $publishEndDate;
    }

    /**
     * Get publishEndDate
     *
     * @return datetime $publishEndDate
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * Set createdDate
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return datetime $createdDate
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param datetime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = new \DateTime("now");//$updatedDate;
    }

    /**
     * Get updatedDate
     *
     * @return datetime $updatedDate
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set parent
     *
     * @param Pages $parent
     */
    public function setParent(\Pages $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Pages $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set revision
     *
     * @param Revisions $revision
     */
    public function setRevision(\Revisions $revision)
    {
        $this->revision = $revision;
    }

    /**
     * Get revision
     *
     * @return Revisions $revision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set route
     *
     * @param Routes $route
     */
    public function setRoute(\Routes $route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return Routes $route
     */
    public function getRoute()
    {
        return $this->route;
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