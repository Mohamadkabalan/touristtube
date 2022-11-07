<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersPermissions
 *
 * @ORM\Table(name="cms_users_permissions", indexes={@ORM\Index(name="user_id", columns={"user_id", "permission_type"})})
 * @ORM\Entity
 */
class CmsUsersPermissions
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="permission_type", type="string", length=32, nullable=false)
     */
    private $permissionType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="permission_value", type="boolean", nullable=false)
     */
    private $permissionValue;



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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsUsersPermissions
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set permissionType
     *
     * @param string $permissionType
     *
     * @return CmsUsersPermissions
     */
    public function setPermissionType($permissionType)
    {
        $this->permissionType = $permissionType;

        return $this;
    }

    /**
     * Get permissionType
     *
     * @return string
     */
    public function getPermissionType()
    {
        return $this->permissionType;
    }

    /**
     * Set permissionValue
     *
     * @param boolean $permissionValue
     *
     * @return CmsUsersPermissions
     */
    public function setPermissionValue($permissionValue)
    {
        $this->permissionValue = $permissionValue;

        return $this;
    }

    /**
     * Get permissionValue
     *
     * @return boolean
     */
    public function getPermissionValue()
    {
        return $this->permissionValue;
    }
}
