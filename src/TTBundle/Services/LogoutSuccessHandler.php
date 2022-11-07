<?php

namespace TTBundle\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Entity\CmsUsers;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router, EntityManager $em, ContainerInterface $container)
    {
        $this->router    = $router;
        $this->em        = $em;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $uuid = $request->cookies->get('lt', '');
        $this->em->getRepository('TTBundle:CmsTubers')->userEndSession($uuid);
        $this->em->getRepository('TTBundle:CmsTubers')->userEndSession(session_id());
        setcookie("lt", '', time() - 3600, '/', $this->container->getParameter('CONFIG_COOKIE_PATH'));

        setcookie("keepme_logged", '', time() - 3600, '/', $this->container->getParameter('CONFIG_COOKIE_PATH'));

        setcookie("REMEMBERME", '', time() - 3600, '/', $this->container->getParameter('CONFIG_COOKIE_PATH'));

        //destroying all session
        $session  = $request->getSession();
        $ses_vars = $session->all();
        foreach ($ses_vars as $key => $value) {
            $session->remove($key);
        }
        $session->clear();
        session_destroy();
        
        $isCorporate = false;
        $requester     = explode("/", $request->server->get('REQUEST_URI', ''));
        if (in_array('corporate', $requester)) {
             $isCorporate = true;
        }
        
        if ($isCorporate){
            return new RedirectResponse($this->router->generate('login'));
        }else{
            return new RedirectResponse($this->router->generate('_log_in'));
        }
        
    }
}
