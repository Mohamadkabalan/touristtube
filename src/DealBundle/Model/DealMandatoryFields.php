<?php

namespace DealBundle\Model;

/**
 * DealMandatoryFields contains the mandatory fields for quote.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealMandatoryFields
{
    private $fieldId           = '';
    private $key               = '';
    private $label             = '';
    private $per               = '';
    private $format            = '';
    private $data              = '';
    private $fieldName         = '';
    private $containerDiv      = '';
    private $containerDivClass = '';
    private $options           = '';
    private $placeholder       = '';
    private $fieldAnswers      = '';
    private $bookingQuoteId    = '';

    /**
     * Get fieldId
     * @return String
     */
    function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Get key
     * @return String
     */
    function getKey()
    {
        return $this->key;
    }

    /**
     * Get label
     * @return String
     */
    function getLabel()
    {
        return $this->label;
    }

    /**
     * Get per
     * @return String
     */
    function getPer()
    {
        return $this->per;
    }

    /**
     * Get format
     * @return String
     */
    function getFormat()
    {
        return $this->format;
    }

    /**
     * Get data
     * @return String
     */
    function getData()
    {
        return $this->data;
    }

    function getFieldName()
    {
        return $this->fieldName;
    }

    function getContainerDiv()
    {
        return $this->containerDiv;
    }

    function getContainerDivClass()
    {
        return $this->containerDivClass;
    }

    function getOptions()
    {
        return $this->options;
    }

    function getPlaceholder()
    {
        return $this->placeholder;
    }

    function getFieldAnswers()
    {
        return $this->fieldAnswers;
    }

    function getBookingQuoteId()
    {
        return $this->bookingQuoteId;
    }

    /**
     * Set fieldId
     * @param String $fieldId
     */
    function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
    }

    /**
     * Set key
     * @param String $key
     */
    function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set label
     * @param String $label
     */
    function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Set per
     * @param String $per
     */
    function setPer($per)
    {
        $this->per = $per;
    }

    /**
     * Set format
     * @param String $format
     */
    function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Set data
     * @param String $data
     */
    function setData($data)
    {
        $this->data = $data;
    }

    function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    function setContainerDiv($containerDiv)
    {
        $this->containerDiv = $containerDiv;
    }

    function setContainerDivClass($containerDivClass)
    {
        $this->containerDivClass = $containerDivClass;
    }

    function setOptions($options)
    {
        $this->options = $options;
    }

    function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    function setFieldAnswers($fieldAnswers)
    {
        $this->fieldAnswers = $fieldAnswers;
    }

    function setBookingQuoteId($bookingQuoteId)
    {
        $this->bookingQuoteId = $bookingQuoteId;
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