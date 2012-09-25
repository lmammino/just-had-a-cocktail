<?php

namespace LMammino\Bundle\JHACBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LMamminoJHACBundle extends Bundle
{
    public static function getImageDir()
    {
        return '/bundles/lmamminojhac/images/';
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
