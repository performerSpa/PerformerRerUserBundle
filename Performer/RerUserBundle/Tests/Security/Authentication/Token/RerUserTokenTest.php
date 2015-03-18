<?php

namespace Performer\RerUserBundle\Tests\Security\Authentication\Token;

use Performer\RerUserBundle\Security\Authentication\Token\RerUserToken;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class RerUserTokenTest extends PHPUnit_Framework_TestCase
{
    public function testGetCredentials()
    {
        $token = new RerUserToken('pippo', 'password', 'domain');
        $this->assertEquals('', $token->getCredentials());
    }
}
