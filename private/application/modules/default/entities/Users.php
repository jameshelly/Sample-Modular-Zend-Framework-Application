<?php
namespace Application\Entities;

use Doctrine\Common\Collections\ArrayCollection, Gedmo\Timestampable\Timestampable;
/**
 * Users
 *
 * @Table(name="users")
 * @Entity
 */
class Users implements Timestampable
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
     * @var string $email
     *
     * @Column(name="email", type="text", nullable=true)
     */
    private $email;

    /**
     * @var string $username
     *
     * @Column(name="username", type="text", nullable=true)
     */
    private $username;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @Column(name="salt", type="string", length=128, nullable=true)
     */
    private $salt;

    /**
     * @var Roles
     *
     * @ManyToOne(targetEntity="Roles")
     */
    private $role;

    /**
     * @var string $firstname
     *
     * @Column(name="firstname", type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @Column(name="lastname", type="string", length=128, nullable=true)
     */
    private $lastname;
    
    /**
     * @gedmo:Timestampable(on="update")
     * dates which should be updated on update and insert
     * @var timestamp $action
     *
     * @Column(type="datetime", nullable=false)
     */
    private $modified;
    
    /**
     * @gedmo:Timestampable(on="create")
     * dates which should be updated on insert only
     * @var timestamp $action
     *
     * @Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var integer $logins
     *
     * @Column(name="logins", type="integer", nullable=true)
     */
    private $logins;

    /**
     * @var datetime $lastLogin
     *
     * @Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;
    
     /**
     *
     * @param type $name
     * @param type $value 
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    /**
     *
     * @param type $name
     * @return type 
     */
    public function __get($name) {
        return $this->$name;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        //$this->password = $password;
        #Auto hash.
        $this->password = $this->generatePassword($password);
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Generate password
     *
     * @param string $password
     * @return string $password
     */
    protected function generatePassword($password)
    {
	return md5($password.$this->getSalt());
    }

    /**
     * Check password
     *
     * @param string $password
     * @return bool $check
     */
    public function checkPassword($password)
    {
	$check = $this->generatePassword($password);
        return ($this->password == $check);
    }
}