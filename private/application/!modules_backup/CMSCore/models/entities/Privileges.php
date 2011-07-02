<?php



/**
 * Privileges
 *
 * @Table(name="privileges")
 * @Entity
 */
class Privileges
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
     * @var aclresource
     *
     * @ManyToOne(targetEntity="Aclresources")
     * @JoinColumns({
     *   @JoinColumn(name="aclresource_id", referencedColumnName="id")
     * })
     */
    private $aclresource;
    
    
    /**
     * @var Role
     *
     * @ManyToOne(targetEntity="Roles")
     */
    private $role;
    
    
    /**
     * @var privilegetypes
     *
     * @ManyToOne(targetEntity="Privilegetypes")
     * @JoinColumns({
     *   @JoinColumn(name="privilegetypes_id", referencedColumnName="id")
     * })
     */
    private $privilegetypes;
    
   
    /**
     * @var allowed
     *
     * @Column(name="allowed", type="integer", nullable=false)
     */
    private $allowed;

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
     * Set privilege
     *
     * @param string $privilegetypes
     */
    public function setPrivilegeType(\Privilegetypes $privilegetypes)
    {
        $this->privilegetypes = $privilegetypes;
    }

    /**
     * Get privilege
     *
     * @return string $privilegetypes
     */
    public function getPrivilegeType()
    {
        return $this->privilegetypes;
    }
    

    /**
     * Set aclresource
     *
     * @param string $resource
     */
    public function setResource(\Aclresources $resource)
    {
        $this->aclresource = $resource;
    }

    /**
     * Get aclresource
     *
     * @return string $aclresource
     */
    public function getResource()
    {
        return $this->aclresource;
    }
    
    /**
     * Set role
     *
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return string $role
     */
    public function getRole()
    {
        return $this->role;
    }
    

    /**
     * Set allowed
     *
     * @param string $allowed
     */
    public function setAllowed($allowed)
    {
        $this->allowed = $allowed;
    }

    /**
     * Get allowed
     *
     * @return string $allowed
     */
    public function getAllowed()
    {
        return $this->allowed;
    }

    
}