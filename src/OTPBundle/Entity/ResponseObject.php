<?php

namespace OTPBundle\Entity;

class ResponseObject
{
    private $success;
    private $code;
    private $message;
    private $data;
    
    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
    
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    
    /**
     *
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }
    
    /**
     *
     * @param mixed $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
    
    /**
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     *
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}