<?php

/**
 * Sites
 *
 * @Table(name="sites")
 * @Entity
 */
class Sites
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Routes
     *
     * @ManyToMany(targetEntity="Routes", inversedBy="site")
     * @JoinTable(name="sites_routes",
     *   joinColumns={
     *     @JoinColumn(name="site_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="route_id", referencedColumnName="id")
     *   }
     * )
     */
    private $route;

    /**
     * @var Settings
     *
     * @ManyToMany(targetEntity="Settings", inversedBy="site")
     * @JoinTable(name="sites_settings",
     *   joinColumns={
     *     @JoinColumn(name="site_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="setting_id", referencedColumnName="id")
     *   }
     * )
     */
    private $setting;

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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add route
     *
     * @param Routes $route
     */
    public function addRoute(\Routes $route)
    {
        $this->route[] = $route;
    }

    /**
     * Get route
     *
     * @return Doctrine\Common\Collections\Collection $route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add setting
     *
     * @param Settings $setting
     */
    public function addSetting(\Settings $setting)
    {
        $this->setting[] = $setting;
    }

    /**
     * Get setting
     *
     * @return Doctrine\Common\Collections\Collection $setting
     */
    public function getSetting()
    {
        return $this->setting;
    }
}