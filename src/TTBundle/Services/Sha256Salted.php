<?php

namespace TTBundle\Services;

use TTBundle\Entity\CmsUsers;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

if (!defined('RESPONSE_SUCCESS')) define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR')) define('RESPONSE_ERROR', 1);

class Sha256Salted extends BasePasswordEncoder
{
    private $ignorePasswordCase;
    protected $em;
    protected $container;
    protected $request;

    /**
     * Constructor.
     *
     * @param bool $ignorePasswordCase Compare password case-insensitive
     */
    public function __construct(ContainerInterface $container, EntityManager $em, $ignorePasswordCase = false)
    {
        $this->ignorePasswordCase = $ignorePasswordCase;
        $this->em                 = $em;
        $this->container          = $container;
        $this->request            = Request::createFromGlobals();
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        // if empty salt then it is using old password format
        if (empty($salt)) {
            $result = $this->em->getRepository('TTBundle:CmsUsers')->generatePassword($raw);
            if (!empty($result) && isset($result[0])) {
                return $result[0]['yourPass'];
            } else {
                return false;
            }
        }
        return sha1($this->mergePasswordAndSalt($raw, $salt));
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            return false;
        }
        try {
            $pass2 = $this->encodePassword($raw, $salt);
        } catch (BadCredentialsException $e) {
            return false;
        }

        $validPassword = (!$this->ignorePasswordCase) ? $this->comparePasswords($encoded, $pass2) : $this->comparePasswords(strtolower($encoded), strtolower($pass2));

        // if empty salt and correct password then it is using old password format
        if ($validPassword && empty($salt)) {
            $params = $this->request->request->all();
            if (isset($params['_username'])) {
                $userInfo = $this->container->get('userServices')->getUserByEmailYourUserName($params['_username']);
                if ($userInfo) {
                    $this->container->get('userServices')->updatePasswordNewFormat($userInfo['cc_id'], $raw);
                }
            }
        }

        return $validPassword;
    }

    /**
     * Merges a password and a salt.
     *
     * @param string $password the password to be used
     * @param string $salt     the salt to be used
     *
     * @return string a merged password and salt
     *
     * @throws \InvalidArgumentException
     */
    protected function mergePasswordAndSalt($password, $salt)
    {
        if (empty($salt)) {
            return $password;
        }

        return $salt.$password;
    }
}
