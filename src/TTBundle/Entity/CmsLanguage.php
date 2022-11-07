<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsLanguage
 *
 * @ORM\Table(name="cms_language")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class CmsLanguage
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
     * @ORM\Column(name="code", type="string", length=2, nullable=false)
     */
    private $code;
    /**
     * @var string
     *
     * @ORM\Column(name="web_code", type="string", length=3, nullable=false)
     */
    private $webCode;
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=50, nullable=false)
     */
    private $label;
    /**
     * @var boolean
     *
     * @ORM\Column(name="needs_login", type="integer", nullable=false)
     */
    private $needsLogin = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set code
     *
     * @param string $code
     *
     * @return CmsLanguage
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set webCode
     *
     * @param string $webCode
     *
     * @return CmsLanguage
     */
    public function setWebCode($webCode)
    {
        $this->webCode = $webCode;

        return $this;
    }

    /**
     * Get webCode
     *
     * @return string
     */
    public function getWebCode()
    {
        return $this->webCode;
    }
    
    /**
     * Set label
     *
     * @param string $label
     *
     * @return CmsLanguage
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set needsLogin
     *
     * @param boolean $needsLogin
     *
     * @return CmsLanguage
     */
    public function setNeedsLogin($needsLogin)
    {
        $this->needsLogin = $needsLogin;

        return $this;
    }

    /**
     * Get needsLogin
     *
     * @return boolean
     */
    public function getNeedsLogin()
    {
        return $this->needsLogin;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsLanguage
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
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
}
