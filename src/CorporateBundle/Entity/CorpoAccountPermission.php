<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccountPermission
 * 
 * @ORM\Table(name="corpo_account_permission")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAccountPermissionRepository")
 */
class CorpoAccountPermission
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
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer", nullable=false)
     */
    private $menuId;

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
     * Get accountId
     *
     * @return integer
     */
    function getAccountId()
    {
        return $this->accountId;
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
     * Set accountId
     *
     * @param string $accountId
     *
     * @return accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }

    /**
     * Set menuId
     *
     * @param string $menuId
     *
     * @return menuId
     */
    function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this->menuId;
    }
}