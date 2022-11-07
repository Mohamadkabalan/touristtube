<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsQueriesLog
 *
 * @ORM\Table(name="cms_queries_log", indexes={@ORM\Index(name="query_string", columns={"query_string"})})
 * @ORM\Entity
 */
class CmsQueriesLog
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
     * @ORM\Column(name="query_string", type="string", length=32, nullable=false)
     */
    private $queryString;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="query_ts", type="datetime", nullable=false)
     */
    private $queryTs = 'CURRENT_TIMESTAMP';



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
     * Set queryString
     *
     * @param string $queryString
     *
     * @return CmsQueriesLog
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * Get queryString
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Set queryTs
     *
     * @param \DateTime $queryTs
     *
     * @return CmsQueriesLog
     */
    public function setQueryTs($queryTs)
    {
        $this->queryTs = $queryTs;

        return $this;
    }

    /**
     * Get queryTs
     *
     * @return \DateTime
     */
    public function getQueryTs()
    {
        return $this->queryTs;
    }
}
