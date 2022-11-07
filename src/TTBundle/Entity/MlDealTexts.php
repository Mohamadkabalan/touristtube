<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MlDealTexts
 *
 * @ORM\Table(name="ml_deal_texts")
 * @ORM\Entity
 */
class MlDealTexts
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_code", type="string", length=45, nullable=false)
     */
    private $dealCode;

    /**
     * @var text
     *
     * @ORM\Column(name="deal_name", type="text", nullable=true)
     */
    private $dealName;

    /**
     * @var text
     *
     * @ORM\Column(name="deal_highlights", type="text", nullable=true)
     */
    private $dealHighlights;

    /**
     * @var text
     *
     * @ORM\Column(name="deal_description", type="text", nullable=true)
     */
    private $dealDescription;

    /**
     * @var text
     *
     * @ORM\Column(name="deal_city", type="text", nullable=true)
     */
    private $dealCity;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_code", type="string", length=2, nullable=false)
     */
    private $langCode;

    function getId()
    {
        return $this->id;
    }

    function getDealCode()
    {
        return $this->dealCode;
    }

    function getDealName()
    {
        return $this->dealName;
    }

    function getDealHighlights()
    {
        return $this->dealHighlights;
    }

    function getDealDescription()
    {
        return $this->dealDescription;
    }

    function getDealCity()
    {
        return $this->dealCity;
    }

    function getLangCode()
    {
        return $this->langCode;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealCode($dealCode)
    {
        $this->dealCode = $dealCode;
    }

    function setDealName($dealName)
    {
        $this->dealName = $dealName;
    }

    function setDealHighlights($dealHighlights)
    {
        $this->dealHighlights = $dealHighlights;
    }

    function setDealDescription($dealDescription)
    {
        $this->dealDescription = $dealDescription;
    }

    function setDealCity($dealCity)
    {
        $this->dealCity = $dealCity;
    }

    function setLangCode($langCode)
    {
        $this->langCode = $langCode;
    }
}