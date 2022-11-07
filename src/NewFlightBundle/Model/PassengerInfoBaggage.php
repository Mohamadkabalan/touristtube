<?php

namespace NewFlightBundle\Model;

class PassengerInfoBaggage extends flightVO
{
    /**
     * 
     */
    private $weight = '';

    /**
     * 
     */
    private $unit = '';

    /**
     * 
     */
    private $piece = '';

    /**
     * 
     */
    private $description = '';

    /**
     * 0: leaving, 1: returning
     */
    private $isReturning = 0;

    /**
     * Get weight
     * @return decimal
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get unit
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get piece
     * @return integer
     */
    public function getPiece()
    {
        return $this->piece;
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
     * Set weight
     * @param decimal $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Set unit
     * @param integer $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * Set piece
     * @param integer $piece
     */
    public function setPiece($piece)
    {
        $this->piece = $piece;
    }

    /**
     * Set description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get isReturning
     * @return integer
     */
    public function getIsReturning()
    {
        return $this->isReturning;
    }

    /**
     * Set isReturning
     * @param integer $isReturning: 0 - leaving, 1 returning
     */
    public function setIsReturning($isReturning)
    {
        $this->isReturning = $isReturning;
    }
}
