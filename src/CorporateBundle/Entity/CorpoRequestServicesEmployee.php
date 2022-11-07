<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoRequestServicesEmployee
 *
 * @ORM\Table(name="corpo_request_services_employee")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoRequestServicesEmployeeRepository")
 */
class CorpoRequestServicesEmployee
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
     * @ORM\Column(name="employee_id", type="integer", nullable=false)
     */
    private $employeeId;

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
     * Get employeeId
     *
     * @return integer
     */
    function getEmployeeId()
    {
        return $this->employeeId;
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
     * Set employeeId
     *
     * @param integer $employeeId
     *
     * @return employeeId
     */
    function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this->employeeId;
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