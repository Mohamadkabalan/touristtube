<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialPermissions
 *
 * @ORM\Table(name="cms_social_permissions", uniqueConstraints={@ORM\UniqueConstraint(name="permission_index", columns={"from_user", "to_user", "perm_fk", "perm_type"})}, indexes={@ORM\Index(name="to_user", columns={"to_user"})})
 * @ORM\Entity
 */
class CmsSocialPermissions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="from_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fromUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="to_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $toUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="perm_fk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $permFk;

    /**
     * @var string
     *
     * @ORM\Column(name="perm_type", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $permType;



    /**
     * Set fromUser
     *
     * @param integer $fromUser
     *
     * @return CmsSocialPermissions
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return integer
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param integer $toUser
     *
     * @return CmsSocialPermissions
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return integer
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set permFk
     *
     * @param integer $permFk
     *
     * @return CmsSocialPermissions
     */
    public function setPermFk($permFk)
    {
        $this->permFk = $permFk;

        return $this;
    }

    /**
     * Get permFk
     *
     * @return integer
     */
    public function getPermFk()
    {
        return $this->permFk;
    }

    /**
     * Set permType
     *
     * @param string $permType
     *
     * @return CmsSocialPermissions
     */
    public function setPermType($permType)
    {
        $this->permType = $permType;

        return $this;
    }

    /**
     * Get permType
     *
     * @return string
     */
    public function getPermType()
    {
        return $this->permType;
    }
}
