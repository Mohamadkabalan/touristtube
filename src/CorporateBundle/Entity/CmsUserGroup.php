<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUserGroup
 *
 * @ORM\Table(name="cms_user_group")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\UserRepository")
 */
class CmsUserGroup
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
     * @ORM\Column(name="name", type="30", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToOne(targetEntity="CmsUsers", mappedBy="cmsUserGroup", cascade={"persist"})
     */
    private $cmsUsers;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get cms_users
     *
     * @return CmsUsers $cmsUsers
     */
    public function getCmsUsers()
    {
        return $this->cmsUsers;
    }

    /**
     * Set cms_users
     *
     * @param CmsUsers $cmsUsers
     */
    public function setCmsUsers(CmsUsers $cmsUsers)
    {
        $cmsUsers->setCmsUsers($this);
        $this->cmsUsers = $cmsUsers;
    }
}