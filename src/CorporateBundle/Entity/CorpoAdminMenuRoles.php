<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAdminMenuRoles
 *
 * @ORM\Table(name="corpo_admin_menu_roles")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAdminMenuRolesRepository")
 */
class CorpoAdminMenuRoles
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
     * @ORM\Column(name="cms_user_group_id", type="integer", nullable=false)
     */
    private $cmsUserGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="corpo_admin_menu_id", type="integer", nullable=false)
     */
    private $corpoAdminMenuId;

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
     * Get cmsUserGroupId
     *
     * @return integer
     */
    function getCmsUserGroupId()
    {
        return $this->cmsUserGroupId;
    }

    /**
     * Get corpoAdminMenuId
     *
     * @return integer
     */
    function getCorpoAdminMenuId()
    {
        return $this->corpoAdminMenuId;
    }

    /**
     * Set cmsUserGroupId
     *
     * @param integer $cmsUserGroupId
     *
     * @return id
     */
    function setCmsUserGroupId($cmsUserGroupId)
    {
        $this->cmsUserGroupId = $cmsUserGroupId;

        return $this->cmsUserGroupId;
    }

    /**
     * Set corpoAdminMenuId
     *
     * @param integer $corpoAdminMenuId
     *
     */
    function setCorpoAdminMenuId($corpoAdminMenuId)
    {
        $this->corpoAdminMenuId = $corpoAdminMenuId;

        return $this->corpoAdminMenuId;
    }
}
