<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialSharesEmails
 *
 * @ORM\Table(name="cms_social_shares_emails", indexes={@ORM\Index(name="share_id", columns={"share_id"})})
 * @ORM\Entity
 */
class CmsSocialSharesEmails
{
    /**
     * @var integer
     *
     * @ORM\Column(name="share_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $shareId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=128)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userEmail;



    /**
     * Set shareId
     *
     * @param integer $shareId
     *
     * @return CmsSocialSharesEmails
     */
    public function setShareId($shareId)
    {
        $this->shareId = $shareId;

        return $this;
    }

    /**
     * Get shareId
     *
     * @return integer
     */
    public function getShareId()
    {
        return $this->shareId;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return CmsSocialSharesEmails
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }
}
