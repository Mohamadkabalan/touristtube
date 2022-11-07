<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDetailsTmp
 *
 * @ORM\Table(name="deal_details_tmp")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealDetailsTmp
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
     * @ORM\Column(name="deal_code", type="string", length=45, nullable=true)
     */
    private $dealCode;

    function getId()
    {
        return $this->id;
    }

    function getDealCode()
    {
        return $this->dealCode;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealCode($dealCode)
    {
        $this->dealCode = $dealCode;
    }
}