<?php

namespace Performer\RerUserBundle\Test;

use Symfony\Component\BrowserKit\Cookie;
use Performer\RerUserBundle\Security\Authentication\Token\RerUserToken;

/**
 * Trait to use custom token in functional tests
 * You need a Symfony Client inside $this->client proprerty
 */
trait RerUserTestTrait
{
    /**
     * @param string $username
     * @param string $firewall
     * @param array  $roles
     */
    protected function rerTokenLogin($username = 'admin', $firewall = 'rer', $roles = ['ROLE_USER'])
    {
        $session = $this->client->getContainer()->get('session');
        $token = new RerUserToken($username, 'foo', 'bar', $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
