<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsBag
 *
 * @ORM\Table(name="cms_bag")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\UserLoginRepository")
 */
class CmsBag
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
     * @ORM\Column(name="imgname", type="string", length=255, nullable=false)
     */
    private $imgname;

    /**
     * @var string
     *
     * @ORM\Column(name="imgpath", type="string", length=255, nullable=false)
     */
    private $imgpath;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_shares", type="integer", nullable=false)
     */
    private $nbShares = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

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
     * Set imgpath
     *
     * @param string $imgpath
     *
     * @return CmsBag
     */
    public function setImgpath($imgpath)
    {
        $this->imgpath = $imgpath;

        return $this;
    }

    /**
     * Get imgpath
     *
     * @return string
     */
    public function getImgpath()
    {
        return $this->imgpath;
    }

    /**
     * Set imgname
     *
     * @param string $imgname
     *
     * @return CmsBag
     */
    public function setImgname($imgname)
    {
        $this->imgname = $imgname;

        return $this;
    }

    /**
     * Get imgname
     *
     * @return string
     */
    public function getImgname()
    {
        return $this->imgname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CmsBag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsBag
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set nbShares
     *
     * @param integer $nbShares
     *
     * @return CmsBag
     */
    public function setNbShares($nbShares)
    {
        $this->nbShares = $nbShares;

        return $this;
    }

    /**
     * Get nbShares
     *
     * @return integer
     */
    public function getNbShares()
    {
        return $this->nbShares;
    }

    /**
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return CmsBag
     */
    public function setCreateTs($createTs)
    {
        $this->createTs = $createTs;

        return $this;
    }

    /**
     * Get createTs
     *
     * @return \DateTime
     */
    public function getCreateTs()
    {
        return $this->createTs;
    }
}