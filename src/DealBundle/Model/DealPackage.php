<?php

namespace DealBundle\Model;

/**
 * DealPackage contains the attributes for package.
 *
 * @author Anna Lou Parejo <anna.parejo@touristtube.com>
 */
class DealPackage
{
    /**
     * @var integer
     */
    private $id = 0;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $code = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $highlights = '';

    /**
     * @var date
     */
    private $startDate = '';

    /**
     * @var date
     */
    private $endDate = '';

    /**
     * @var decimal
     */
    private $price = '';

    /**
     * @var integer
     */
    private $apiId = '';

    /**
     * @var integer
     */
    private $typeId = '';

    /**
     * @var string (packages,tours,activities,transfers or attractions)
     */
    private $typeName = '';

    /**
     * @var string
     */
    private $currency = '';

    /**
     * Get id
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return String
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get code
     * @return String
     */
    function getCode()
    {
        return $this->code;
    }

    /**
     * Get description
     * @return String
     */
    function getDescription()
    {
        return $this->description;
    }

    /**
     * Get highlights
     * @return String
     */
    function getHighlights()
    {
        return $this->highlights;
    }

    /**
     * Get startdate
     * @return String
     */
    function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Get enddate
     * @return String
     */
    function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get price
     * @return decimal
     */
    function getPrice()
    {
        return $this->price;
    }

    /**
     * Get apiId
     * @return integer
     */
    function getApiId()
    {
        return $this->apiId;
    }

    /**
     * Get typeId
     * @return integer
     */
    function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Get typeName
     * @return string
     */
    function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Get currency
     * @return string
     */
    function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set id
     * @param integer id
     */
    function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name
     * @param String name
     */
    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set code
     * @param String code
     */
    function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Set description
     * @param String description
     */
    function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set highlights
     * @param String highlights
     */
    function setHighlights($highlights)
    {
        $this->highlights = $highlights;
    }

    /**
     * Set startdate
     * @param date startdate
     */
    function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Set enddate
     * @param date enddate
     */
    function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Set price
     * @param decimal price
     */
    function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Set apiId
     * @param integer apiId
     */
    function setApiId($apiId)
    {
        $this->apiId = $apiId;
    }

    /**
     * Set typeId
     * @param integer typeId
     */
    function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * Set typeName
     * @param string typeName
     */
    function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

    /**
     * Set currency
     * @param string currency
     */
    function setCurrency($currency)
    {
        $this->currency = $currency;
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