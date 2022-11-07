<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccount
 *
 * @ORM\Table(name="corpo_account_type_menu")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAccountTypeMenuRepository")
 */
class CorpoAccountTypeMenu
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
     * @ORM\Column(name="account_type_id", type="integer", length=100, nullable=false)
     */
    private $accountTypeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="menu_id", type="integer", length=255, nullable=false)
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
     * Get accountTypeId
     *
     * @return integer
     */
    function getAccountTypeId()
    {
        return $this->accountTypeId;
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
     * Set accountTypeId
     *
     * @param string $accountTypeId
     *
     * @return accountTypeId
     */
    function setAccountTypeId($accountTypeId)
    {
        $this->accountTypeId = $accountTypeId;

        return $this->accountTypeId;
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
