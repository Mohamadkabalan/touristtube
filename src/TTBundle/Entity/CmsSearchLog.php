<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSearchLog
 *
 * @ORM\Table(name="cms_search_log", indexes={@ORM\Index(name="query_string", columns={"search_string"})})
 * @ORM\Entity
 */
class CmsSearchLog
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
     * @ORM\Column(name="search_type", type="string", length=255, nullable=false)
     */
    private $searchType;

    /**
     * @var string
     *
     * @ORM\Column(name="search_string", type="string", length=255, nullable=false)
     */
    private $searchString;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="search_ts", type="datetime", nullable=false)
     */
    private $searchTs = 'CURRENT_TIMESTAMP';



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set searchType
     *
     * @param string $searchType
     *
     * @return CmsSearchLog
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;

        return $this;
    }

    /**
     * Get searchType
     *
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * Set searchString
     *
     * @param string $searchString
     *
     * @return CmsSearchLog
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;

        return $this;
    }

    /**
     * Get searchString
     *
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * Set searchTs
     *
     * @param \DateTime $searchTs
     *
     * @return CmsSearchLog
     */
    public function setSearchTs($searchTs)
    {
        $this->searchTs = $searchTs;

        return $this;
    }

    /**
     * Get searchTs
     *
     * @return \DateTime
     */
    public function getSearchTs()
    {
        return $this->searchTs;
    }
}
