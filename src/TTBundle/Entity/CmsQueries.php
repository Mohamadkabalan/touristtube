<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsQueries
 *
 * @ORM\Table(name="cms_queries")
 * @ORM\Entity
 */
class CmsQueries
{
    /**
     * @var string
     *
     * @ORM\Column(name="query_string", type="string", length=32)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $queryString;

    /**
     * @var integer
     *
     * @ORM\Column(name="n_occurrence", type="integer", nullable=false)
     */
    private $nOccurrence = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="trend_candidate", type="smallint", nullable=false)
     */
    private $trendCandidate = '0';



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
     * Set nOccurrence
     *
     * @param integer $nOccurrence
     *
     * @return CmsQueries
     */
    public function setNOccurrence($nOccurrence)
    {
        $this->nOccurrence = $nOccurrence;

        return $this;
    }

    /**
     * Get nOccurrence
     *
     * @return integer
     */
    public function getNOccurrence()
    {
        return $this->nOccurrence;
    }

    /**
     * Set trendCandidate
     *
     * @param integer $trendCandidate
     *
     * @return CmsQueries
     */
    public function setTrendCandidate($trendCandidate)
    {
        $this->trendCandidate = $trendCandidate;

        return $this;
    }

    /**
     * Get trendCandidate
     *
     * @return integer
     */
    public function getTrendCandidate()
    {
        return $this->trendCandidate;
    }
}
