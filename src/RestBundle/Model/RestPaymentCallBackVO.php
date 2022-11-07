<?php

namespace RestBundle\Model;

class RestPaymentCallBackVO
{
    private $moduleId;
    private $moduleTransactionId;
    private $paymentId;
    private $vendor;

    /**
     * Get moduleId
     * @return moduleId
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set moduleId
     * @param $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * Get moduleTransactionId
     * @return moduleTransactionId
     */
    public function getModuleTransactionId()
    {
        return $this->moduleTransactionId;
    }

    /**
     * Set moduleTransactionId
     * @param $moduleTransactionId
     */
    public function setModuleTransactionId($moduleTransactionId)
    {
        $this->moduleTransactionId = $moduleTransactionId;
    }

    /**
     * Get paymentId
     * @return paymentId
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set paymentId
     * @param $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Get vendor
     * @return vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set vendor
     * @param $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }
}
