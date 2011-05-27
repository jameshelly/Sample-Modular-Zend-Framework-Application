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
     * @var text $action
     *
     * @Column(name="action", type="text", nullable=true)
     */
    private $action;

    /**
     * @var Roles
     *
     * @ManyToMany(targetEntity="Roles", mappedBy="permission")
     */
    private $role;

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
    public function setAction($action)
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