<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoApprovalFlow
 *
 * @ORM\Table(name="corpo_approval_flow")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAdminApprovalFlowRepository")
 */
class CorpoApprovalFlow
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approval_flow_parent", type="boolean", nullable=false)
     */
    private $approvalFlowParent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="approval_flow_root", type="boolean", nullable=false)
     */
    private $approvalFlowRoot = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="approve_all_users", type="boolean", nullable=false)
     */
    private $approveAllUsers = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="approval_flow_user", type="boolean", nullable=false)
     */
    private $approvalFlowUser = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="main_user_id", type="integer", nullable=false)
     */
    private $mainUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="other_user_id", type="integer", nullable=false)
     */
    private $otherUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=50, nullable=true)
     */
    private $path;

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
        return $this->$accountId;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Get approvalFlowParent
     *
     * @return boolean
     */
    function getApprovalFlowParent()
    {
        return $this->approvalFlowParent;
    }

    /**
     * Get approvalFlowRoot
     *
     * @return boolean
     */
    function getApprovalFlowRoot()
    {
        return $this->approvalFlowRoot;
    }

    /**
     * Get approvalFlowUser
     *
     * @return boolean
     */
    function getApprovalFlowUser()
    {
        return $this->approvalFlowUser;
    }

    /**
     * Get approveAllUsers
     *
     * @return boolean
     */
    function getApproveAllUsers()
    {
        return $this->approveAllUsers;
    }

    /**
     * Get otherUserId
     *
     * @return integer
     */
    function getOtherUserId()
    {
        return $this->otherUserId;
    }

    /**
     * Get mainUserId
     *
     * @return integer
     */
    function getMainUserId()
    {
        return $this->mainUserId;
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
     * @param integer $accountId
     *
     * @return accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return userId
     */
    function setUserId($userId)
    {
        $this->userId = $userId;

        return $this->userId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return parentId
     */
    function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this->parentId;
    }

    /**
     * Set approvalFlowParent
     *
     * @param boolean $approvalFlowParent
     *
     * @return approvalFlowParent
     */
    public function setApprovalFlowParent($approvalFlowParent)
    {
        $this->approvalFlowParent = $approvalFlowParent;

        return $this->approvalFlowParent;
    }

    /**
     * Set approvalFlowRoot
     *
     * @param boolean $approvalFlowRoot
     *
     * @return approvalFlowRoot
     */
    public function setApprovalFlowRoot($approvalFlowRoot)
    {
        $this->approvalFlowRoot = $approvalFlowRoot;

        return $this->approvalFlowRoot;
    }

    /**
     * Set approveAllUsers
     *
     * @param boolean approveAllUsers
     *
     * @return approveAllUsers
     */
    public function setApproveAllUsers($approveAllUsers)
    {
        $this->approveAllUsers = $approveAllUsers;

        return $this->approveAllUsers;
    }

    /**
     * Set approvalFlowUser
     *
     * @param boolean approvalFlowUser
     *
     * @return approvalFlowUser
     */
    public function setApprovalFlowUser($approvalFlowUser)
    {
        $this->approvalFlowUser = $approvalFlowUser;

        return $this->approvalFlowUser;
    }

    /**
     * Set mainUserId
     *
     * @param integer $mainUserId
     *
     * @return mainUserId
     */
    function setMainUserId($mainUserId)
    {
        $this->mainUserId = $mainUserId;

        return $this->mainUserId;
    }

    /**
     * Set otherUserId
     *
     * @param integer $otherUserId
     *
     * @return otherUserId
     */
    function setOtherUserId($otherUserId)
    {
        $this->otherUserId = $otherUserId;

        return $this->otherUserId;
    }

    /**
     * Get path
     *
     * @return integer
     */
    function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return path
     */
    function setPath($path)
    {
        $this->path = $path;

        return $this->path;
    }
}