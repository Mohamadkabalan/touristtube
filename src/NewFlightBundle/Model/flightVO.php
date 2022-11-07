<?php

namespace NewFlightBundle\Model;

class flightVO
{
    static $codes = array(
        100 => 'Found',
        101 => 'No Results',
        102 => 'Error on Session',
        103 => 'Error on Search',
    );

    /**
     *
     */
    private $status;

    /**
     *
     */
    private $message;

    /**
     *
     */
    private $code;

    /**
     * @var data
     */
    private $data;

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    function setData($data)
    {
        $this->data = $data;
    }
}
