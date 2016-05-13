<?php
namespace ApiBundle\Controller\Traits;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Symfony\Component\HttpFoundation\Response;

trait SerializeTrait {

    protected function serializedResponse($data, $groups = [], Response $response = null)
    {
        /** @var Response $response */
        $response or $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $jsonString = $this->serializeJms($data, $groups);

        return $response->setContent($jsonString);
    }

    protected function serializeJms($data, array $groups = [])
    {
        /** @var Serializer $serializer */
        $serializer = $this->container->get('jms_serializer');

        // Content
        $serializationContext = $groups
            ? SerializationContext::create()->setGroups($groups)
            : null;

        $jsonString = $serializer->serialize($data, 'json', $serializationContext);

        return $jsonString;
    }
}