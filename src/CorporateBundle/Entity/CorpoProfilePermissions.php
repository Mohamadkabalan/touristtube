<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccount
 *
 * @ORM\Table(name="corpo_user_profile_menus_permission")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoProfilePermissionsRepository")
 */
class CorpoProfilePermissions
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
     * @var integer
     *
     * @ORM\Column(name="user_profile_id", type="integer", nullable=false)
     */
    private $profileId;

    /**
     * @var integer
     *
     * @ORM\Column(name="corpo_menu_id", type="integer", nullable=false)
     */
    private $menuId;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=false)
     */
    private $updatedBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=false)
     */
    private $updatedOn;

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
     * Get profileId
     *
     * @return integer
     */
    function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * Get menuId
     *
     * @return integer
     */
    function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Get updatedBy
     *
     * @return integer
     */
    function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Get updatedOn
     *
     * @return DateTime
     */
    function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * Set profileId
     *
     * @param integer $profileId
     *
     * @return profileId
     */
    function setProfileId($profileId)
    {
        $this->profileId = $profileId;

        return $this->profileId;
    }

    /**
     * Set menuId
     *
     * @param integer $menuId
     *
     * @return menuId
     */
    function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this->menuId;
    }

    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     *
     * @return updatedBy
     */
    function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this->updatedBy;
    }

    /**
     * Set updatedOn
     *
     * @param DateTime $updatedOn
     *
     * @return updatedOn
     */
    function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this->updatedOn;
    }
}
