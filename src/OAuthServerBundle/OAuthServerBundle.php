<?php

namespace OAuthServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OAuthServerBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
