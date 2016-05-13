<?php

namespace ApiBundle\Security;

use ApiBundle\Entity\Client;
use FOS\OAuthServerBundle\Model\AccessTokenManagerInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

class CorsManager
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;
    /**
     * @var AccessTokenManagerInterface
     */
    private $accessTokenManager;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ClientManagerInterface $clientManager, AccessTokenManagerInterface $accessTokenManager, Logger $logger)
    {

        $this->clientManager = $clientManager;
        $this->accessTokenManager = $accessTokenManager;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param $origin
     * @return bool
     */
    public function isValid(Request $request, $origin)
    {
        // check for Client Access
        if ($authorizationHeader = $request->headers->get('authorization')) {
            $parts = explode(' ', $authorizationHeader);
            if (count($parts) == 2 and $parts[0] == 'Bearer') {

                if ($accessToken = $this->accessTokenManager->findTokenByToken($parts[1])) {
                    $clientId = $accessToken->getClientId();
                    return $this->validateOrigin($clientId, $origin);
                }
                return false;
            }
        }
        // Check for ClientId
        if ($clientId = $request->get('client_id')) {
            return $this->validateOrigin($clientId, $origin);
        }

        return true;
    }

    protected function validateOrigin($clientId, $origin)
    {
        /** @var Client $client */
        if (!$client = $this->clientManager->findClientByPublicId($clientId)) {
            return false;
        }

        if (array_search($origin, $client->getAllowedOrigins()) === false) {
            return false;
        }

        return true;
    }

}
