<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAdminUsers
 *
 * @ORM\Table(name="corpo_admin_users")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAdminUsersRepository")
 */
class CorpoAdminUsers
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
     * @ORM\Column(name="fname", type="string", length=20, nullable=false)
     */
    private $fname;

    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=20, nullable=false)
     */
    private $lname;

    /**
     * @var integer
     *
     * @ORM\Column(name="password", type="integer", nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get fname
     *
     * @return string
     */
    function getFname()
    {
        return $this->fname;
    }

    /**
     * Get lname
     *
     * @return string
     */
    function getLname()
    {
        return $this->lname;
    }

    /**
     * Get password
     *
     * @return string
     */
    function getPassword()
    {
        return $this->password;
    }

    /**
     * Get email
     *
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * Get salt
     *
     * @return string
     */
    function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set fname
     *
     * @param string $fname
     *
     * @return fname
     */
    function setFname($fname)
    {
        $this->fname = $fname;

        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return lname
     */
    function setLname($lname)
    {
        $this->lname = $lname;

        return $this->lname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return password
     */
    function setPassword($password)
    {
        $this->password = $password;

        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return salt
     */
    function setSalt($salt)
    {
        $this->salt = $salt;

        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return email
     */
    function setEmail($email)
    {
        $this->email = $email;

        return $this->email;
    }
}