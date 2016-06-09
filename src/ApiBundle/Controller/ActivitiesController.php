<?php

namespace ApiBundle\Controller;

use ApiBundle\Controller\Traits\SerializeTrait;
use ApiBundle\Entity\Activity;
use ApiBundle\Entity\Channel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class ActivitiesController extends Controller
{
    use SerializeTrait;

    public function indexAction(Request $request)
    {
        $page = abs($request->query->getInt('page', 1));
        $perPage = abs($request->query->getInt('per_page', 20));

        if ($perPage > $this->getParameter('api.index.max_per_page')) {
            throw new InvalidParameterException('`per_page` parameter exceeds maximum limit');
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $client = $this->getClient($request);

        $paginator = $em->getRepository(Activity::class)->getPaginatedByClient($client, $page, $perPage);

        return $this->paginatedResponse($paginator, ['activity', 'activity-user', 'user']);
    }

    private function getClient(Request $request)
    {
        if ($key = $request->get('x-client-key')) {
            return $this->get('fos_oauth_server.client_manager')->findClientByPublicId($key);
        }
    }
}
