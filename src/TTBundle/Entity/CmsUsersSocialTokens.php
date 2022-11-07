<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersSocialTokens
 *
 * @ORM\Table(name="cms_users_social_tokens")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\UserRepository")
 */
class CmsUsersSocialTokens
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
     * @ORM\Column(name="account_type", type="string", length=10, nullable=false)
     */
    private $accountType;

    /**
     * @var integer
     *
     * @ORM\Column(name="social_id", type="integer", nullable=false)
     */
    private $socialId;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_token", type="string", length=255, nullable=false)
     */
    private $oauthToken;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_token_secret", type="string", length=255, nullable=false)
     */
    private $oauthTokenSecret;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;



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
     * @return CmsUsersSocialTokens
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
     * Set accountType
     *
     * @param string $accountType
     *
     * @return CmsUsersSocialTokens
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set socialId
     *
     * @param integer $socialId
     *
     * @return CmsUsersSocialTokens
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;

        return $this;
    }

    /**
     * Get socialId
     *
     * @return integer
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * Set oauthToken
     *
     * @param string $oauthToken
     *
     * @return CmsUsersSocialTokens
     */
    public function setOauthToken($oauthToken)
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    /**
     * Get oauthToken
     *
     * @return string
     */
    public function getOauthToken()
    {
        return $this->oauthToken;
    }

    /**
     * Set oauthTokenSecret
     *
     * @param string $oauthTokenSecret
     *
     * @return CmsUsersSocialTokens
     */
    public function setOauthTokenSecret($oauthTokenSecret)
    {
        $this->oauthTokenSecret = $oauthTokenSecret;

        return $this;
    }

    /**
     * Get oauthTokenSecret
     *
     * @return string
     */
    public function getOauthTokenSecret()
    {
        return $this->oauthTokenSecret;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CmsUsersSocialTokens
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
