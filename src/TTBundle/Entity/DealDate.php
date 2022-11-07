<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDate
 *
 * @ORM\Table(name="deal_date", indexes={@ORM\Index(name="deal_id", columns={"deal_id"})})
 * @ORM\Entity
 */
class DealDate
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
     * @var integer
     *
     * @ORM\Column(name="deal_id", type="integer", nullable=false)
     */
    private $dealId='0';
	
    /**
     * @var \Date
     *
     * @ORM\Column(name="from_date", type="date", nullable=false)
     */
    private $fromDate;
	
    /**
     * @var \Date
     *
     * @ORM\Column(name="to_date", type="date", nullable=false)
     */
    private $toDate;

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
     * Set dealId
     *
     * @param integer $dealId
     *
     * @return DealDate
     */
    public function setDealId($dealId)
    {
        $this->dealId = $dealId;

        return $this;
    }

    /**
     * Get dealId
     *
     * @return integer
     */
    public function getDealId()
    {
        return $this->dealId;
    }

    /**
     * Set fromDate
     *
     * @param string $fromDate
     *
     * @return DealDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param string $toDate
     *
     * @return DealDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
    }
}
