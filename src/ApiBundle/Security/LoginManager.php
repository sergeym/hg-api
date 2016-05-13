<?php

namespace ApiBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * Abstracts process for manually logging in a user.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class LoginManager implements LoginManagerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    private $userChecker;
    private $sessionStrategy;
    private $container;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserCheckerInterface $userChecker,
        SessionAuthenticationStrategyInterface $sessionStrategy,
        ContainerInterface $container)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userChecker = $userChecker;
        $this->sessionStrategy = $sessionStrategy;
        $this->container = $container;
    }

    final public function logInUser($firewallName, UserInterface $user, Response $response = null)
    {
        $this->userChecker->checkPostAuth($user);

        $token = $this->createToken($firewallName, $user);

        if ($this->container->isScopeActive('request')) {
            $this->sessionStrategy->onAuthentication($this->container->get('request'), $token);

            if (null !== $response) {
                $rememberMeServices = null;
                if ($this->container->has('security.authentication.rememberme.services.persistent.'.$firewallName)) {
                    $rememberMeServices = $this->container->get('security.authentication.rememberme.services.persistent.'.$firewallName);
                } elseif ($this->container->has('security.authentication.rememberme.services.simplehash.'.$firewallName)) {
                    $rememberMeServices = $this->container->get('security.authentication.rememberme.services.simplehash.'.$firewallName);
                }

                if ($rememberMeServices instanceof RememberMeServicesInterface) {
                    $rememberMeServices->loginSuccess($this->container->get('request'), $response, $token);
                }
            }
        }

        $this->tokenStorage->setToken($token);
    }

    protected function createToken($firewall, UserInterface $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}