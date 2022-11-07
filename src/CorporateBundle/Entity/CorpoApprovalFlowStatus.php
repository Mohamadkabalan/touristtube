<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoApprovalFlowStatus
 *
 * @ORM\Table(name="corpo_approval_flow_status")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAdminApprovalFlowRepository")
 */
class CorpoApprovalFlowStatus
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
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=false)
     */
    private $sortOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_approved", type="boolean", nullable=false)
     */
    private $isApproved = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_canceled", type="boolean", nullable=false)
     */
    private $isCanceled = '0';

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
     * Get sortOrder
     *
     * @return integer
     */
    function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Get isApproved
     *
     * @return boolean
     */
    function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * Get isCanceled
     *
     * @return boolean
     */
    function getIsCanceled()
    {
        return $this->isCanceled;
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
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return sortOrder
     */
    function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this->sortOrder;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return isApproved
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this->isApproved;
    }

    /**
     * Set isCanceled
     *
     * @param boolean $approvalFlowRoot
     *
     * @return isCanceled
     */
    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;

        return $this->isCanceled;
    }
}