<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoDepartments
 *
 * @ORM\Table(name="corpo_departments")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoDepartmentsRepository")
 */
class CorpoDepartments
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
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

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
     * Get accountId
     *
     * @return integer
     */
    function getAccountId()
    {
        return $this->accountId;
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
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }
}