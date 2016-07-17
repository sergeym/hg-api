<?php

namespace ApiBundle\Controller;

use ApiBundle\Controller\Traits\OAuthTrait;
use ApiBundle\Controller\Traits\SerializeTrait;
use ApiBundle\Entity\Activity;
use ApiBundle\Entity\Channel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class ActivitiesController extends Controller
{
    use SerializeTrait, OAuthTrait;

    protected $embeddedGroups = [
        'user'                    => ['activity-user', 'user'],
        'first-location'          => ['activity-first-location', 'location-id'],
        'first-location.location' => ['location'],
        'last-location'           => ['activity-last-location', 'location-id'],
        'last-location.location'  => ['location'],
        'equipment'               => ['activity-equipment', 'equipment'],
        'equipment.type'          => ['equipment-type'],
        'equipment.brand'         => ['equipment-brand', 'brand']
    ];

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->get('request_stack')->getCurrentRequest();
        $page = abs($request->query->getInt('page', 1));
        $perPage = abs($request->query->getInt('per_page', 20));
        $embed = $request->query->get('embed', null);

        if ($perPage > $this->getParameter('api.index.max_per_page')) {
            throw new InvalidParameterException('`per_page` parameter exceeds maximum limit');
        }

        $activityRepository = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Activity::class);

        $paginator = $activityRepository->getPaginatedByClient(
            $this->getClient(),
            $page,
            $perPage
        );

        $jmsGroups = $this->embedToJmsGroups(explode(',', $embed));

        return $this->paginatedResponse($paginator, array_merge(['activity'], $jmsGroups));
    }

    /**
     * @param Request $request
     * @param Activity $activity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request, Activity $activity)
    {
        //todo check activity owned by user in client channels
        $embed = $request->query->get('embed', null);
        $jmsGroups = $this->embedToJmsGroups(explode(',', $embed));

        return $this->serializedResponse($activity, array_merge(['activity'], $jmsGroups));
    }

    /**
     * @param array $embed
     * @return array
     */
    protected function embedToJmsGroups(array $embed):array 
    {
        $result = [];

        foreach ($embed as $embedIndex) {
            $result = array_merge($result, $this->embeddedGroups[$embedIndex] ?? []);
        }

        return $result;
    }
}
