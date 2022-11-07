<?php

namespace RestBundle\Controller\oauth;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\OAuthServerBundle\Model\TokenInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



/**
 * Class OAuth2TokenController
 * @package TTBundle\Controller
 *
 */
class OAuth2TokenController extends Controller
{

    /**
     * Get access token
     * @param Request $request
     * @return TokenInterface
     *
     */
    public function tokenAction(Request $request)
    {
        $server = $this->get('fos_oauth_server.server');
        try {
            return $server->grantAccessToken($request);
        } catch (OAuth2ServerException $e) {
            return $e->getHttpResponse();
        }
    }

    /**
     * Creating a new client
     * @param Request $request
     *
     */
    public function createClientAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        try {
            $client = $clientManager->createClient();
            $client->setRedirectUris(array());
            $client->setAllowedGrantTypes(array('password', 'refresh_token'));
            $clientManager->updateClient($client);

            $jsonContent = $serializer->serialize($client, 'json');

            $ret = new Response($jsonContent);
            $ret->headers->set('Content-Type', 'application/json');

            return $ret;
        } catch (OAuth2ServerException $e) {
            return $e->getHttpResponse();
        }
    }
}
