<?php

namespace NewFlightBundle\Model;

class Penalty extends flightVO
{
    /**
     * 
     */
    private $type = '';

    /**
     * 
     */
    private $applicability = '';

    /**
     * 
     */
    private $conditionApply = '';

    /**
     * 
     */
    private $amount = '';

    /**
     * 
     */
    private $currency = '';

    /**
     * 
     */
    private $description = '';

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get applicability
     * @return string
     */
    public function getApplicability()
    {
        return $this->applicability;
    }

    /**
     * Get conditionApply
     * @return string
     */
    public function getConditionApply()
    {
        return $this->conditionApply;
    }

    /**
     * Get amount
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set applicability
     * @param string $applicability
     */
    public function setApplicability($applicability)
    {
        $this->applicability = $applicability;
    }

    /**
     * Set conditionApply
     * @param string $conditionApply
     */
    public function setConditionApply($conditionApply)
    {
        $this->conditionApply = $conditionApply;
    }

    /**
     * Set amount
     * @param decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set currency
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Set description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}