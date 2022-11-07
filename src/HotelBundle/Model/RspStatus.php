<?php

namespace HotelBundle\Model;

/**
 * The RspStatus class
 */
class RspStatus
{
    private $status;
    private $error;
    private $errorLogFile;

    /**
     * The __construct when we make a new instance of RspStatus class.
     */
    public function __construct()
    {
        $this->status = true;
    }

    /**
     * Get status.
     * @return Boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get error.
     * @return Mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Gets the error message only.
     * @return Mixed
     */
    public function getErrorMessage()
    {
        if (is_object($this->getError())) {
            $this->setError(get_object_vars($this->getError()));
        }

        if (is_array($this->getError())) {
            if (isset($this->getError()['message'])) {
                return $this->getError()['message'];
            }
        }

        return $this->getError();
    }

    /**
     * Get errorLogFile.
     * @return String
     */
    public function getErrorLogFile()
    {
        return $this->errorLogFile;
    }

    /**
     * Set status.
     * @param Boolean $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set error
     * @param Mixed $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error  = $error;
        $this->status = false;
        return $this;
    }

    /**
     * Set error log file
     * @param String $errorLogFile
     * @return $this
     */
    public function setErrorLogFile($errorLogFile)
    {
        $this->errorLogFile = $errorLogFile;
        return $this;
    }

    /**
     * Converts an object to an array.
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
