<?php

namespace Performer\RerUserBundle\Security\Firewall;

use Performer\RerUserBundle\Security\Authentication\Token\RerUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * RerUserListener
 */
class RerUserListener extends AbstractAuthenticationListener
{
    /**
     * {@inheritdoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        $username = trim($request->get('_username', null, true));
        $password = $request->get('_password', null, true);
        $domain = $request->get('_domain', null, true);

        $request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);

        return $this->authenticationManager->authenticate(new RerUserToken($username, $password, $domain));
    }
}
