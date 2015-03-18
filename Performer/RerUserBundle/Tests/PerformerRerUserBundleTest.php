<?php

namespace Performer\RerUserBundle\Tests;

use Performer\RerUserBundle\PerformerRerUserBundle;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class PerformerRerUserBundleTest extends PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()->getMock();
        $extension = $this->getMockBuilder('Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension')
            ->disableOriginalConstructor()->getMock();

        $container->expects($this->once())->method('getExtension')->will($this->returnValue($extension));
        $extension->expects($this->once())->method('addSecurityListenerFactory');

        $bundle = new PerformerRerUserBundle();
        $bundle->build($container);
    }
}
