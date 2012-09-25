<?php

namespace LMammino\Bundle\JHACBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;

class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $setter = 'set'.ucfirst($property);

        if (!method_exists($user, $setter)) {
            throw new \RuntimeException(sprintf("Class '%s' should have a method '%s'.", get_class($user), $setter));
        }

        $username = $response->getUsername();

        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter(null);
            $this->userManager->updateUser($previousUser);
        }

        $user->$setter($username);

        $serviceAccessTokenName = $response->getResourceOwner()->getName() . 'AccessToken';
        $serviceAccessTokenSetter = 'set' . ucfirst($serviceAccessTokenName);

        if(method_exists($user, $serviceAccessTokenSetter))
            $user->$serviceAccessTokenSetter($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

}