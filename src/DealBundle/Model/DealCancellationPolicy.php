<?php

namespace DealBundle\Model;

/**
 * DealCancellationPolicy is used to save the details of cancellationPolicy.
 * We have an attribute for this on main class DealResponse called $cancellationPolicy.
 * we manipulate the response of this inside parseCancellationPolicy().
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealCancellationPolicy
{
    private $cancellationDay      = '';
    private $cancellationDiscount = '';

    /**
     * Get cancellationDay
     * @return String
     */
    function getCancellationDay()
    {
        return $this->cancellationDay;
    }

    /**
     * Get cancellationDiscount
     * @return String
     */
    function getCancellationDiscount()
    {
        return $this->cancellationDiscount;
    }

    /**
     * Set cancellationDay
     * @param String $cancellationDay
     */
    function setCancellationDay($cancellationDay)
    {
        $this->cancellationDay = $cancellationDay;
    }

    /**
     * Set cancellationDiscount
     * @param String $cancellationDiscount
     */
    function setCancellationDiscount($cancellationDiscount)
    {
        $this->cancellationDiscount = $cancellationDiscount;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}