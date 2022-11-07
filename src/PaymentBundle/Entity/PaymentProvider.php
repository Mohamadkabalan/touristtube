<?php

namespace PaymentBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentProvider
 *
 * @ORM\Table(name="payment_provider")
 * @ORM\Entity
 */
class PaymentProvider
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $name;

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }
}
