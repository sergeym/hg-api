<?php
namespace ApiBundle\Controller\Traits;

use ApiBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

/**
 * Class OAuthTrait
 * @package ApiBundle\Controller\Traits
 * @mixin
 */
trait OAuthTrait {

    /**
     * @return Client
     * @see get() Controller::get()
     * @access protected
     */
    public function getClient():Client
    {
        /** @var Request $request */
        $request = $this->get('request_stack')->getCurrentRequest();
        if ($key = $request->headers->get('x-client-key')) {
            if ($client = $this->get('fos_oauth_server.client_manager')->findClientByPublicId($key)) {
                return $client;
            }
        }

        throw new PreconditionFailedHttpException('No valid client key found');
    }
}