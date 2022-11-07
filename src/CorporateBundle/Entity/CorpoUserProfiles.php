<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccount
 *
 * @ORM\Table(name="corpo_user_profiles")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoUserProfilesRepository")
 */
class CorpoUserProfiles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean", nullable=false)
     */
    private $published = false;

    /**
     * @var string
     *
     * @ORM\Column(name="section_title", type="string", length=100, nullable=true)
     */
    private $sectionTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="integer", length=11, nullable=true)
     */
    private $level;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get sectionTitle
     *
     * @return string
     */
    function getSectionTitle()
    {
        return $this->sectionTitle;
    }

    /**
     * Get slug
     *
     * @return string
     */
    function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return name
     */
    function setName($name)
    {
        $this->name = $name;

        return $this->name;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return id
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Set sectionTitle
     *
     * @param string $sectionTitle
     *
     * @return sectionTitle
     */
    function setSectionTitle($sectionTitle)
    {
        $this->sectionTitle = $sectionTitle;

        return $this->sectionTitle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return slug
     */
    function setSlug($slug)
    {
        $this->slug = $slug;

        return $this->slug;
    }

    /**
     * Get level
     *
     * @return integer
     */
    function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return level
     */
    function setLevel($level)
    {
        $this->level = $level;
    }
}
