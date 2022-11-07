<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PassengerTypeQuote
 *
 * @ORM\Table(name="passenger_type_quote")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class PassengerTypeQuote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="module_id", type="integer", nullable=false)
     */
    private $moduleId='1';

    /**
     * @var integer
     *
     * @ORM\Column(name="module_transaction_id", type="integer", nullable=false)
     */
    private $moduleTransactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="passenger_type", type="string", length=3, nullable=false)
     */
    private $passengerType='ADT';

    /**
     * @var string
     *
     * @ORM\Column(name="price_quote", type="string", nullable=false)
     */
    private $priceQuote;

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
     * Set moduleId
     *
     * @param integer $moduleId
     *
     * @return PassengerTypeQuote
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;

        return $this;
    }

    /**
     * Get moduleId
     *
     * @return integer
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set moduleTransactionId
     *
     * @param integer $moduleTransactionId
     *
     * @return PassengerTypeQuote
     */
    public function setModuleTransactionId($moduleTransactionId)
    {
        $this->moduleTransactionId = $moduleTransactionId;

        return $this;
    }

    /**
     * Get moduleTransactionId
     *
     * @return integer
     */
    public function getModuleTransactionId()
    {
        return $this->moduleTransactionId;
    }

    /**
     * Set passengerType
     *
     * @param string $passengerType
     *
     * @return PassengerTypeQuote
     */
    public function setPassengerType($passengerType)
    {
        $this->passengerType = $passengerType;

        return $this;
    }

    /**
     * Get passengerType
     *
     * @return string
     */
    public function getPassengerType()
    {
        return $this->passengerType;
    }

    /**
     * Set priceQuote
     *
     * @param string $priceQuote
     *
     * @return PassengerTypeQuote
     */
    public function setPriceQuote($priceQuote)
    {
        $this->priceQuote = $priceQuote;

        return $this;
    }

    /**
     * Get priceQuote
     *
     * @return string
     */
    public function getPriceQuote()
    {
        return $this->priceQuote;
    }
}
