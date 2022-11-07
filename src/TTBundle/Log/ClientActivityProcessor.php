<?php

namespace TTBundle\Log;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class ClientActivityProcessor
{
    private $requestStack;
    private $cachedClientIp = null;
    private $session;
    private $token;

    public function __construct(RequestStack $requestStack, Session $session)
    {
        $this->requestStack = $requestStack;
        $this->session      = $session;
    }

    public function __invoke(array $record)
    {
        // request_ip will hold our proxy server's IP
        // $record['extra']['request_ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unavailable';
        // client_ip will hold the request's actual origin address
        $record['extra']['client_ip'] = $this->cachedClientIp ? $this->cachedClientIp : 'unavailable';

        // Return if we already know client's IP
        if ($record['extra']['client_ip'] !== 'unavailable') {
            return $record;
        }

        // Ensure we have a request (maybe we're in a console command)
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return $record;
        }

        // If we do, get the client's IP, and cache it for later.
        $this->cachedClientIp         = $request->getClientIp();
        $record['extra']['client_ip'] = $this->cachedClientIp;

        if (null === $this->token) {
            try {
                $this->token = substr($this->session->getId(), 0, 8);
            } catch (\RuntimeException $e) {
                $this->token = '????????';
            }
            $this->token .= '-'.substr(uniqid(), -8);
        }
        $record['extra']['token'] = $this->token;

        return $record;
    }
}