<?php

namespace HotelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends \TTBundle\Controller\DefaultController
{
    protected $userId = null;
    protected $logger;
    protected $request;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->logger  = $this->container->get('HotelLogger');
        $this->request = Request::createFromGlobals();
    }

    public function getUserId()
    {
        if (!isset($this->userId)) {
            $this->userId = intval($this->userGetID());
        }
        return $this->userId;
    }
}
