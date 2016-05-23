<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ApiBundle\Controller\Traits\SerializeTrait;

class UserController extends Controller
{
    use SerializeTrait;
    
    public function profileAction()
    {
        return $this->serializedResponse($this->getUser(), ['user']);
    }
}
