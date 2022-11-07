<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersPrivacyExtand
 *
 * @ORM\Table(name="cms_users_privacy_extand", indexes={@ORM\Index(name="entity_id", columns={"entity_id", "entity_type", "published"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="entity_id_2", columns={"entity_id"}), @ORM\Index(name="entity_type", columns={"entity_type"}), @ORM\Index(name="entity_id_3", columns={"entity_id", "entity_type"}), @ORM\Index(name="user_id_2", columns={"user_id", "entity_id", "entity_type"})})
 * @ORM\Entity
 */
class CmsUsersPrivacyExtand
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
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="integer", nullable=false)
     */
    private $entityType;

    /**
     * @var string
     *
     * @ORM\Column(name="kind_type", type="text", length=65535, nullable=false)
     */
    private $kindType;

    /**
     * @var string
     *
     * @ORM\Column(name="users", type="text", length=65535, nullable=false)
     */
    private $users;

    /**
     * @var string
     *
     * @ORM\Column(name="users_list", type="text", length=65535, nullable=false)
     */
    private $usersList;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsUsersPrivacyExtand
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
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return CmsUsersPrivacyExtand
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityType
     *
     * @param integer $entityType
     *
     * @return CmsUsersPrivacyExtand
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return boolean
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set kindType
     *
     * @param string $kindType
     *
     * @return CmsUsersPrivacyExtand
     */
    public function setKindType($kindType)
    {
        $this->kindType = $kindType;

        return $this;
    }

    /**
     * Get kindType
     *
     * @return string
     */
    public function getKindType()
    {
        return $this->kindType;
    }

    /**
     * Set users
     *
     * @param string $users
     *
     * @return CmsUsersPrivacyExtand
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set usersList
     *
     * @param string $usersList
     *
     * @return CmsUsersPrivacyExtand
     */
    public function setUsersList($usersList)
    {
        $this->usersList = $usersList;

        return $this;
    }

    /**
     * Get usersList
     *
     * @return string
     */
    public function getUsersList()
    {
        return $this->usersList;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsUsersPrivacyExtand
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
