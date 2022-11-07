<?php

namespace DealBundle\Model;

/**
 * DealTransferType contains the attributes for transfer type.
 *
 * @author Anna Lou Parejo <anna.parejo@touristtube.com>
 */
class DealTransferType
{
    /**
     * @var integer
     */
    private $id = 0;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $code = '';

    /**
     * Get id
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return String
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get code
     * @return String
     */
    function getCode()
    {
        return $this->code;
    }

    /**
     * Set id
     * @param integer id
     */
    function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name
     * @param String name
     */
    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set code
     * @param String code
     */
    function setCode($code)
    {
        $this->code = $code;
    }
}