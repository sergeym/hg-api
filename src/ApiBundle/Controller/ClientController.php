<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Client;
use ApiBundle\Form\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ApiBundle\Controller\Traits\SerializeTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\InvalidParameterException;


class ClientController extends Controller
{
    use SerializeTrait;
    
    public function indexAction(Request $request)
    {
        $page = abs($request->query->getInt('page', 1));
        $perPage = abs($request->query->getInt('per_page', 20));

        if ($perPage > $this->getParameter('api.index.max_per_page')) {
            throw new InvalidParameterException('`per_page` parameter exceeds maximum limit');
        }

        $paginator = $this->getDoctrine()->getRepository(Client::class)->getPaginatedByUser($this->getUser(), $page, $perPage);

        return $this->paginatedResponse($paginator, ['client-private']);
    }

    /**
     * @Security("client.hasUser(user)")
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function updateAction(Request $request, Client $client)
    {
        $form = $this->createForm(ClientType::class, $client);

        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
        } else {
            $errors = $form->getErrors(true, true);

            $errorCollection = array();
            foreach($errors as $error){
                $name = $error->getOrigin()->getName();
                if (is_numeric($name)) {
                    $name = $error->getOrigin()->getParent()->getName();
                }

                $errorCollection[$name] = $error->getMessage();
            }

            return $this->serializedResponse(['errors'=>$errorCollection], [], new Response(null, Response::HTTP_BAD_REQUEST));
        }

        return $this->serializedResponse($client, ['client-private']);
    }

    public function getAction(Client $client)
    {
        $clients = $this->get('doctrine.orm.default_entity_manager')->getRepository(Client::class)->findAll();
        
        return $this->serializedResponse($clients, ['client-private']);
    }

    public function clientsAction()
    {

    }
}
