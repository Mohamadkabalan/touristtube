<?php

namespace RestBundle\Services;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;

class ApiKeyUserProvider implements UserProviderInterface
{
    protected $em;

    /**
     * Constructor.
     *
     * @param bool $ignorePasswordCase Compare password case-insensitive
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        //need an object to be passed
        $user = $this->em->getRepository('TTBundle:CmsUsers')->findOneBy(array('yourusername' => $username));

        if (!$user) {
            $user = $this->em->getRepository('TTBundle:CmsUsers')->findOneBy(array('youremail' => $username));
        }

        if (!$user) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return;
    }
}