<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TTBundle\Model;

use TTBundle\Utils\Utils;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Description of Email
 *
 * @author Lenovo
 */
class Email
{
    /**
     * @var string|null
     * @SerializedName("subject")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $subject;

    /**
     * @var string|null
     * @SerializedName("from")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $from;

    /**
     * @var string|null
     * @SerializedName("to")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $to;

    /**
     * @var array|null
     * @SerializedName("bccs")
     * @Assert\Type("array")
     * @Type("array")
     */
    protected $bccs;

    /**
     * @var string|null
     * @SerializedName("message")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $message;

    /**
     * @var string|null
     * @SerializedName("attachmentPath")
     * @Assert\Type("string = path")
     * @Type("string")
     */
    protected $attachmentPath;

    /**
     * @var LONGBLOB|null
     * @SerializedName("data")
     * @Assert\Type("data")
     * @Type("string")
     */
    protected $data;

    /**
     * @var string |null
     * @SerializedName("dataType")
     * @Assert\Type("string = dataType")
     * @Type("string")
     */
    protected $dataType = 'application/octet-stream';

    /**
     * @var string |null
     * @SerializedName("dataFileName")
     * @Assert\Type("string = dataFileName")
     * @Type("string")
     */
    protected $dataFileName;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->subject            = isset($data['subject']) ? $data['subject'] : null;
        $this->from               = isset($data['from']) ? $data['from'] : null;
        $this->to                 = isset($data['to']) ? $data['to'] : null;
        $this->message            = isset($data['message']) ? $data['message'] : null;
        $this->attachmentPath     = isset($data['attachmentPath']) ? $data['attachmentPath'] : null;
        $this->data               = isset($data['data']) ? $data['data'] : null;
        $this->dataType           = isset($data['dataType']) ? $data['dataType'] : 'application/octet-stream';
        $this->dataFileName       = isset($data['dataFileName']) ? $data['dataFileName'] : null;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getBcc()
    {
        return $this->bccs;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setBcc($bccs)
    {
        $this->bccs = $bccs;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getAttachmentPath()
    {
        return $this->attachmentPath;
    }

    public function setAttachmentPath($attachmentPath)
    {
        $this->attachmentPath = $attachmentPath;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    public function getDataFileName()
    {
        return $this->dataFileName;
    }

    public function setDataFileName($dataFileName)
    {
        $this->dataFileName = $dataFileName;
    }

    /**
     * Convert array to object
     * @param array $params
     */
    public function arrayToObject($params)
    {
        $email = new Email();
        return Utils::array_to_obj($params, $email);
    }
}
