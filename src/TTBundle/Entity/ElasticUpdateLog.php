<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElasticUpdateLog
 *
 * @ORM\Table(name="elastic_update_log")
 * @ORM\Entity
 */
class ElasticUpdateLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="indexName", type="string", length=100, nullable=false)
     */
    private $indexname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdated", type="datetime", nullable=true)
     */
    private $lastupdated = 'CURRENT_TIMESTAMP';



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
     * Set indexname
     *
     * @param string $indexname
     *
     * @return ElasticUpdateLog
     */
    public function setIndexname($indexname)
    {
        $this->indexname = $indexname;

        return $this;
    }

    /**
     * Get indexname
     *
     * @return string
     */
    public function getIndexname()
    {
        return $this->indexname;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     *
     * @return ElasticUpdateLog
     */
    public function setLastupdated($lastupdated)
    {
        $this->lastupdated = $lastupdated;

        return $this;
    }

    /**
     * Get lastupdated
     *
     * @return \DateTime
     */
    public function getLastupdated()
    {
        return $this->lastupdated;
    }
}
