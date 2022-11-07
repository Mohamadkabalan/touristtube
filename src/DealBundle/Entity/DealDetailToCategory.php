<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDetailToCategory
 *
 * @ORM\Table(name="deal_detail_to_category")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealDetailToCategory
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
     * @ORM\Column(name="deal_category_id", type="integer", nullable=true)
     */
    private $dealCategoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_details_id", type="integer", nullable=true)
     */
    private $dealDetailsId;

    function getId()
    {
        return $this->id;
    }

    function getDealCategoryId()
    {
        return $this->dealCategoryId;
    }

    function getDealDetailsId()
    {
        return $this->dealDetailsId;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealCategoryId($dealCategoryId)
    {
        $this->dealCategoryId = $dealCategoryId;
    }

    function setDealDetailsId($dealDetailsId)
    {
        $this->dealDetailsId = $dealDetailsId;
    }
}