<?php

/**
 * Users
 *
 * @Table(name="users")
 * @Entity
 */
class Users
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
     * @Column(name="email", type="string", length=128, nullable=true)
     */
    private $email;

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
     * @var string $username
     *
     * @Column(name="username", type="string", length=128, nullable=true)
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
     * @var Roles
     *
     * @ManyToOne(targetEntity="Roles")
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
     * Set username
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get username
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get username
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set username
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get username
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }


    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
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
        $this->password = md5($password.$this->getSalt());
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

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set logins
     *
     * @param integer $logins
     */
    public function setLogins($logins)
    {
        $this->logins = $logins;
    }

    /**
     * Get logins
     *
     * @return integer $logins
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     * Set lastLogin
     *
     * @param datetime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Get lastLogin
     *
     * @return datetime $lastLogin
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }
    
    
    public function getLastLoginForamt($format){
    	$ret="";
    	$last=$this->getLastLogin();
    	if(!empty($last)){
    		$ret=$last->format($format); 	
    	}
    	return $ret;	
    }

    /**
     * Set role
     *
     * @param Roles $role
     */
    public function setRole(\Roles $role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return Roles $role
     */
    public function getRole()
    {
        return $this->role;
    }
}