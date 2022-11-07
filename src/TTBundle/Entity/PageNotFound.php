<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PageNotFound
 *
 * @ORM\Table(name="page_not_found")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PageNotFound
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
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_seen", type="datetime", nullable=false)
     */
    private $lastSeen; // 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';
	
	/**
	 * @var integer
	 * 
	 * @ORM\Column(name="n", type="integer", nullable=false)
	*/
	private $n = 1;
	
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
     * Set url
     *
     * @param string $url
     *
     * @return PageNotFound
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Set lastSeen
     *
     * @param \DateTime $lastSeen
     *
     * @return PageNotFound
     */
    public function setLastSeen($lastSeen)
    {
        $this->lastSeen = $lastSeen;

        return $this;
    }

    /**
     * Get lastSeen
     *
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->lastSeen;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return PageNotFound
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
	
	/**
	 * Get n
	 *
	 * @return integer
	*/
	public function getN()
	{
		return $this->n;
	}
	
	/**
     * Set n
     *
     * @param integer $n
     *
     * @return PageNotFound
     */
	public function setN($n)
	{
		$this->n = $n;
		
		return $this;
	}
	
	/**
     * Just seen
     *
     * @return PageNotFound
     */
	public function justSeen()
	{
		$this->n++;
		
		$this->lastSeen = new \DateTime;
		
		return $this;
	}
	
	/**
     * @ORM\PrePersist
     */
	 public function onPrePersistSetLastSeen()
	 {
		 $this->lastSeen = new \DateTime;
	 }
}
