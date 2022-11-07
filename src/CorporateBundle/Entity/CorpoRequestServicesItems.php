<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoRequestServicesItems
 *
 * @ORM\Table(name="corpo_request_services_items")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoRequestServicesItemsRepository")
 */
class CorpoRequestServicesItems
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
     * @ORM\Column(name="service_id", type="integer", nullable=false)
     */
    private $serviceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="request_id", type="integer", nullable=false)
     */
    private $requestId;

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
     * Get serviceId
     *
     * @return integer
     */
    function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Get requestId
     *
     * @return integer
     */
    function getRequestId()
    {
        return $this->requestId;
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
     * Set serviceId
     *
     * @param integer $serviceId
     *
     * @return serviceId
     */
    function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;

        return $this->serviceId;
    }

    /**
     * Set requestId
     *
     * @param integer $requestId
     *
     * @return requestId
     */
    function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this->requestId;
    }
}