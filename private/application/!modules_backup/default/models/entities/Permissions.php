<?php

/**
 * Permissions
 *
 * @Table(name="permissions")
 * @Entity
 */
class Permissions
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
     * @var boolean $allowed
     *
     * @Column(name="allowed", type="boolean", nullable=true)
     */
    private $allowed;

    /**
     * @var Roles
     *
     * @ManyToMany(targetEntity="Roles", mappedBy="permission")
     */
    private $role;

    /**
     * @var Actions
     *
     * @ManyToOne(targetEntity="Actions", inversedBy="permissions")
     * @JoinColumns({
     *   @JoinColumn(name="action_id", referencedColumnName="id")
     * })
     */
    private $action;

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
     * Set allowed
     *
     * @param boolean $allowed
     */
    public function setAllowed($allowed)
    {
        $this->allowed = $allowed;
    }

    /**
     * Get allowed
     *
     * @return boolean $allowed
     */
    public function getAllowed()
    {
        return $this->allowed;
    }

    /**
     * Add role
     *
     * @param Roles $role
     */
    public function addRole(\Roles $role)
    {
        $this->role[] = $role;
    }

    /**
     * Get role
     *
     * @return Doctrine\Common\Collections\Collection $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set action
     *
     * @param Actions $action
     */
    public function setAction(\Actions $action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return Actions $action
     */
    public function getAction()
    {
        return $this->action;
    }
}