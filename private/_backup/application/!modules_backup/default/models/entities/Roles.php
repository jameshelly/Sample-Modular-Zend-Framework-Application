<?php

/**
 * Roles
 *
 * @Table(name="roles")
 * @Entity
 */
class Roles
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
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;
    
     /**
     * @var Roles
     *
     * @ManyToOne(targetEntity="Roles")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;
    
    
     /**
     * @var integer $buildorder
     *
     * @Column(name="buildorder", type="integer" )
     */
    private $buildorder;
    
    

    /**
     * @var Permissions
     *
     * @ManyToMany(targetEntity="Permissions", inversedBy="role")
     * @JoinTable(name="roles_permissions",
     *   joinColumns={
     *     @JoinColumn(name="role_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="permission_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permission;

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
     * Add permission
     *
     * @param Permissions $permission
     */
    public function addPermission(\Permissions $permission)
    {
        $this->permission[] = $permission;
    }

    /**
     * Get permission
     *
     * @return Doctrine\Common\Collections\Collection $permission
     */
    public function getPermission()
    {
        return $this->permission;
    }
    
    /**
     * Set parent
     *
     * @param Roles $parent
     */
    public function setParent(\Roles $parent)
    {
        $this->parent = $parent;
    }
      

    /**
     * Get parent
     *
     * @return Roles $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
    
	/**
     * Set buildorder
     *
     * @param integer $buildorder
     */
    public function setBuildOrder($buildorder)
    {
        $this->buildorder = $buildorder;
    }

    /**
     * Get order
     *
     * @return integer $buildorder
     */
    public function getBuildOrder()
    {
    	return $this->buildorder;
    }
    
    
}