<?php
namespace Application\Entities;

use Gedmo\Mapping\Annotation AS Gedmo,
    Doctrine\ORM\Mapping AS ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Gedmo\Timestampable\Timestampable;
/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users implements Timestampable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="update")
     * dates which should be updated on update and insert
     * @var timestamp $action
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @Gedmo\Timestampable(on="create")
     * dates which should be updated on insert only
     * @var timestamp $action
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var Roles
     *
     * @ORM\OneToOne(targetEntity="Roles")
     */
    private $role;

    /**
     * @var integer $logins
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logins;

    /**
     * @var datetime $lastLogin
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @var string $email
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $email;

    /**
     * @var string $username
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $salt;

    /**
     * @var string $firstname
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $lastname;

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
	return md5($password.$this->salt);
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