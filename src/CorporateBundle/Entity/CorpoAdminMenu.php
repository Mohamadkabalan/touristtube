<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAdminMenu
 *
 * @ORM\Table(name="corpo_admin_menu")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAdminMenuRepository")
 */
class CorpoAdminMenu
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
     * @var string
     *
     * @ORM\Column(name="menu_key", type="string", length=50, nullable=false)
     */
    private $menuKey;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="order", type="integer", nullable=false)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="enable_for_mobile", type="integer", nullable=false)
     */
    private $enableForMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_trigger_method", type="integer", nullable=true)
     */
    private $mobileTriggerMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="onclick", type="string", length=100, nullable=true)
     */
    private $onClick;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=50, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="cls", type="string", length=50, nullable=true)
     */
    private $cls;

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
     * Get menuKey
     *
     * @return string
     */
    function getMenuKey()
    {
        return $this->menuKey;
    }

    /**
     * Get url
     *
     * @return string
     */
    function getUrl()
    {
        return $this->url;
    }

    /**
     * Get order
     *
     * @return integer
     */
    function getOrder()
    {
        return $this->order;
    }

    /**
     * Get type
     *
     * @return integer
     */
    function getType()
    {
        return $this->type;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Get published
     *
     * @return integer
     */
    function getPublished()
    {
        return $this->published;
    }

    /**
     * Get enableForMobile
     *
     * @return integer
     */
    function getEnableForMobile()
    {
        return $this->enableForMobile;
    }

    /**
     * Get mobileTriggerMethod
     *
     * @return string
     */
    function getMobileTriggerMethod()
    {
        return $this->mobileTriggerMethod;
    }

    /**
     * Get onClick
     *
     * @return string
     */
    function getOnClick()
    {
        return $this->onClick;
    }

    /**
     * Get path
     *
     * @return string
     */
    function getPath()
    {
        return $this->path;
    }

    /**
     * Get cls
     *
     * @return string
     */
    function getCls()
    {
        return $this->cls;
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
     * Set menuKey
     *
     * @param string $menuKey
     *
     * @return menuKey
     */
    function setMenuKey($menuKey)
    {
        $this->menuKey = $menuKey;

        return $this->menuKey;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return url
     */
    function setUrl($url)
    {
        $this->url = $url;

        return $this->url;
    }

    /**
     * Set order
     *
     * @param string $order
     *
     * @return order
     */
    function setOrder($order)
    {
        $this->order = $order;

        return $this->order;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     */
    function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this->parentId;
    }

    /**
     * Set published
     *
     * @param string $published
     *
     * @return published
     */
    function setPublished($published)
    {
        $this->published = $published;

        return $this->published;
    }

    /**
     * Set enableForMobile
     *
     * @param integer $enableForMobile
     *
     */
    function setEnableForMobile($enableForMobile)
    {
        $this->enableForMobile = $enableForMobile;

        return $this->enableForMobile;
    }

    /**
     * Set mobileTriggerMethod
     *
     * @param string $mobileTriggerMethod
     *
     * @return
     */
    function setMobileTriggerMethod($mobileTriggerMethod)
    {
        $this->mobileTriggerMethod = $mobileTriggerMethod;

        return $this->mobileTriggerMethod;
    }

    /**
     * Set onClick
     *
     * @param string $onClick
     *
     * @return onClick
     */
    function setOnClick($onClick)
    {
        $this->onClick = $onClick;

        return $this->onClick;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return path
     */
    function setPath($path)
    {
        $this->path = $path;

        return $this->path;
    }

    /**
     * Set cls
     *
     * @param string $cls
     *
     * @return cls
     */
    function setCls($cls)
    {
        $this->cls = $cls;

        return $this->cls;
    }
}
