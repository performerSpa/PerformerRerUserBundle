<?php

namespace Performer\RerUserBundle\Tests\Security\Authentication\Factory;

use Performer\RerUserBundle\Security\Factory\RerUserFactory;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class RerUserFactoryTest extends PHPUnit_Framework_TestCase
{
    #public function testCreateAuthProvider()
    #{
    #    $builder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
    #        ->disableOriginalConstructor()->getMock();
    #    $builder->expects($this->once())->method('setDefinition')->will($this->returnSelf());
    #    $builder->expects($this->once())->method('replaceArgument');
    #
    #    $factory = new RerUserFactory();
    #    $factory->createAuthProvider($builder);
    #}

    public function testGetPosition()
    {
        $factory = new RerUserFactory();
        $this->assertEquals('form', $factory->getPosition());
    }

    public function testGetKey()
    {
        $factory = new RerUserFactory();
        $this->assertEquals('rer-login', $factory->getKey());
    }
}
