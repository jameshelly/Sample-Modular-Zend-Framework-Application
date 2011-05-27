<?php

/**
 * Settings
 *
 * @Table(name="settings")
 * @Entity
 */
class Settings
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
     * @var text $param
     *
     * @Column(name="param", type="text", nullable=true)
     */
    private $param;

    /**
     * @var Sites
     *
     * @ManyToMany(targetEntity="Sites", mappedBy="setting")
     */
    private $site;
        
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
     * Set param
     *
     * @param text $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * Get param
     *
     * @return text $param
     */
    public function getParam()
    {
        return $this->param;
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
}