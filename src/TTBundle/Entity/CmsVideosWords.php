<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsVideosWords
 *
 * @ORM\Table(name="cms_videos_words", indexes={@ORM\Index(name="word", columns={"word"}), @ORM\Index(name="vid", columns={"vid"})})
 * @ORM\Entity
 */
class CmsVideosWords
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
     * @ORM\Column(name="vid", type="integer", nullable=false)
     */
    private $vid;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=64, nullable=false)
     */
    private $word;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", nullable=false)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="col_type", type="string", length=1, nullable=false)
     */
    private $colType;



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
     * Set vid
     *
     * @param integer $vid
     *
     * @return CmsVideosWords
     */
    public function setVid($vid)
    {
        $this->vid = $vid;

        return $this;
    }

    /**
     * Get vid
     *
     * @return integer
     */
    public function getVid()
    {
        return $this->vid;
    }

    /**
     * Set word
     *
     * @param string $word
     *
     * @return CmsVideosWords
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return CmsVideosWords
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set colType
     *
     * @param string $colType
     *
     * @return CmsVideosWords
     */
    public function setColType($colType)
    {
        $this->colType = $colType;

        return $this;
    }

    /**
     * Get colType
     *
     * @return string
     */
    public function getColType()
    {
        return $this->colType;
    }
}
