<?php

/**
 * Routes
 * @gedmo:Tree(type="nested")
 * @Table(name="routes")
 * @Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Routes
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
     * @var string $route
     *
     * @Column(name="route", type="string", length=255, nullable=false)
     */
    private $route;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Sites
     *
     * @ManyToMany(targetEntity="Sites", mappedBy="route")
     */
    private $site;

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
     * @ManyToOne(targetEntity="Routes", inversedBy="children")
     */
    private $parent;
     
    /**
     * @OneToMany(targetEntity="Routes", mappedBy="parent")
     * @OrderBy({"lft" = "ASC"})
     */
    private $children;
    
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
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string $route
     */
    public function getRoute()
    {
        return $this->route;
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
     * Add site
     *
     * @param Sites $site
     */
    public function addSite(\Sites $site)
    {
        $this->site[] = $site;
    }

    /**
     * Get site
     *
     * @return Doctrine\Common\Collections\Collection $site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set parent
     *
     * @param Routes $parent
     */
    public function setParent(\Routes $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Routes $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
}