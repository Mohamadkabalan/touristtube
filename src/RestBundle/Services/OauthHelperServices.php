<?php

namespace RestBundle\Services;

use RestBundle\Controller\oauth\OAuth2TokenController;

class OauthHelperServices
{
    protected $OAuth2TokenController;

    public function __construct(OAuth2TokenController $OAuth2TokenController)
    {
        $this->OAuth2TokenController        = $OAuth2TokenController;
    }

    public function getTokenInfo($request)
    {
        $tokenInfo = $this->OAuth2TokenController->tokenAction($request);

        return $tokenInfo;
    }
}