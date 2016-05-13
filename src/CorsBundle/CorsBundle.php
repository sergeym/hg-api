<?php

namespace CorsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CorsBundle extends Bundle
{
    public function getParent()
    {
        return 'NelmioCorsBundle';
    }
}
