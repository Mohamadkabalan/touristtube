<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentDetails

 *
 * @ORM\Entity
 * @ORM\Table(name="payment_details")
 */
class PaymentDetails
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
     * The payment uuid
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=36, nullable=false)
     */
    private $uuid;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="api_response", type="string", nullable=true)
     */
    private $apiResponse;

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
     * Get payment uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set payment uuid
     *
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return updatedAt
     */
    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this->updatedAt;
    }

    /**
     * Set API Response
     *
     * @param json $apiResponse
     */
    public function setApiResponse($apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Get API Response
     *
     * @return json
     */
    public function getApiResponse()
    {
        return $this->apiResponse;
    }
}
