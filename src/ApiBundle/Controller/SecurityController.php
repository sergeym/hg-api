<?php

namespace ApiBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @property ContainerInterface container
 */
class SecurityController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function loginAction(Request $request)
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

        return $this->render('ApiBundle:Security:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction(Request $request)
    {
        $serverService = $this->container->get('fos_oauth_server.server');
        $clientId = null;

        if ($bearerToken = $serverService->getBearerToken($request, true)) {
            $accessTokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
            if ($token = $accessTokenManager->findTokenByToken($bearerToken)) {
                $clientId = $token->getClientId();
                $accessTokenManager->deleteToken($token);
            }
        }

        if ($clientId) {
            $this->container->get('session')->remove('api.user.authorized_token_data.'.$clientId);
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function loginStatusAction(Request $request)
    {
        if (!$clientId = $request->query->get('client_id')) {
            throw new BadRequestHttpException('Client ID required');
        }

        $clientManager = $this->container->get('fos_oauth_server.client_manager');

        if (!$client = $clientManager->findClientByPublicId($clientId)) {
            throw new BadRequestHttpException('Invalid Client ID');
        }

        $status = 'unknown';
        $result = [];
        //$scope = '';
        $user = $this->getTokenStorage()->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $status = 'not_authorized';

            $authManager = $this->container->get('api.security.authorization_manager');
            $accessTokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
            $authorizedClient = $authManager->findByClientAndUser($client, $user);

            //if ($authorizedClient->isScopeValid($request->query->get('scope'))) {
            if ($authorizedClient) {

                if ($tokenData = $this->container->get('session')->get('api.user.authorized_token_data.'.$client->getId())) {
                    if ($accessTokenManager->findTokenByToken($tokenData['access_token'])) {
                        if ($tokenData['expires_in'] > time()) {
                            $result['authResponse'] = $tokenData;
                            $status = 'connected';
                        }
                    } else {
                        $this->container->get('session')->remove('api.user.authorized_token_data.'.$client->getId());
                    }
                }
            }
        }

        $result['status'] = $status;

        return new JsonResponse($result);
    }

    public function receiverAction()
    {
        return $this->render('@Api/Security/receiver.html.twig');
    }

    public function blankAction()
    {
        return new Response('');
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        if ($this->container->has('templating')) {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
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

