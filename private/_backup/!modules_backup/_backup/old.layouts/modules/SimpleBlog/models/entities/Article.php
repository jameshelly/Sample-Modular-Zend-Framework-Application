<?php

/**
 * Settings
 *
 * @Table(name="articles")
 * @Entity
 */
class Articles
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
     * @var string $title
     *
     * @Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var string $link
     *
     * @Column(name="link", type="text", nullable=false)
     */
    private $link;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var datetime $pubDate
     *
     * @Column(name="pubdate", type="datetime", nullable=true)
     */
    private $pubDate;

    /**
     * @var string $guid
     *
     * @Column(name="guid", type="text", nullable=false)
     */
    private $guid;
    
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set param
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get param
     *
     * @return text $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set pubDate
     *
     * @param string $pubDate
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * Get pubDate
     *
     * @return string $pubDate
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Set guid
     *
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * Get guid
     *
     * @return string $guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

}