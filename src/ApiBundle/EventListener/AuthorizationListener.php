<?php

namespace ApiBundle\EventListener;

use ApiBundle\Security\AuthorizationManager;
use FOS\OAuthServerBundle\Event\OAuthEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AuthorizationListener
{
    /** @var AuthorizationManager */
    protected $manager;
    /** @var ContainerInterface */
    protected $container;
    /** @var Request */
    protected $request;

    /**
     * AuthorizationListener constructor.
     * @param ContainerInterface $container
     * @param AuthorizationManager $manager
     */
    public function __construct(ContainerInterface $container, AuthorizationManager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
        $this->request = $this->container->get('request_stack')->getCurrentRequest();
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($this->request->request->has('fos_oauth_server_authorize_form')) {
            $formData = $this->request->request->get('fos_oauth_server_authorize_form');
            if ($authClient = $this->manager->findByClientAndUser($event->getClient(), $event->getUser())) {
                $this->manager->updateScope($authClient, $formData['scope']);
            } else {
                $this->manager->create($event->getClient(), $event->getUser(), $formData['scope']);
            }
        }
    }

    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        if ($this->request->getMethod() == Request::METHOD_GET) {
            if ($authClient = $this->manager->findByClientAndUser($event->getClient(), $event->getUser())) {
                $isScopeValid = $authClient->isScopeValid($this->request->get('scope', null));
                $event->setAuthorizedClient($isScopeValid);
            }
        }

    }

    public function onUserAuthorized(FilterResponseEvent $event)
    {

        if ($event->getRequest()->get('_route') == 'fos_oauth_server_authorize'
        and $event->getRequest()->query->get('response_type') == 'token'
        and $event->getResponse()->getStatusCode() == Response::HTTP_FOUND
        and $location = $event->getResponse()->headers->get('Location')
        and $hash = parse_url($location, PHP_URL_FRAGMENT)
        ) {
            $aHash = [];

            foreach (explode('&', $hash) as $_hashParam) {
                list($k, $v) = explode('=', $_hashParam);
                $aHash[$k] = $v;
            }

            if (array_key_exists('access_token', $aHash)
            and array_key_exists('expires_in', $aHash)
            and array_key_exists('token_type', $aHash)
            and array_key_exists('scope', $aHash)
            ) {
                $clientId = $event->getRequest()->get('client_id');
                list($id) = explode('_', $clientId);
                $this->container->get('session')->set('api.user.authorized_token_data.'.$id, [
                    'access_token' => $aHash['access_token'],
                    'expires_in'   => time()+$aHash['expires_in'],
                    'scope'       => urldecode($aHash['scope']),
                    'user_id'      => $this->getTokenStorage()->getToken()->getUser()->getId(),
                ]);
            }
        }
    }

    /**
     * @return TokenStorage
     */
    private function getTokenStorage()
    {
        if ($this->container->has('security.token_storage')) {
            return $this->container->get('security.token_storage');
        }

        return $this->container->get('security.context');
    }

}