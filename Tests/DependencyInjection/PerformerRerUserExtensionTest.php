<?php

namespace Performer\RerUserBundle\Tests\DependencyInjection;

use Performer\RerUserBundle\DependencyInjection\PerformerRerUserExtension;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class PerformerRerUserExtensionTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFailure()
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()->getMock();
        $extension = $this->getMockBuilder('Performer\RerUserBundle\DependencyInjection\PerformerRerUserExtension')
            ->getMock();

        $extension->load([[]], $container);
    }

    public function testLoadSetParameters()
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()->getMock();
        $parameterBag = $this->getMockBuilder('Symfony\Component\DependencyInjection\ParameterBag\ParameterBag')
            ->disableOriginalConstructor()->getMock();

        $parameterBag->expects($this->any())->method('add');
        $container->expects($this->any())->method('getParameterBag')->will($this->returnValue($parameterBag));

        $extension = new PerformerRerUserExtension();
        $configs = [
            ['user_class' => 'foo'],
            ['soap' => ['id_applicazione' => 'bar', 'salt' => 'ss', 'hash_wsdl' => 'ww', 'auth_wsdl' => 'zz']],
        ];
        $extension->load($configs, $container);
    }
}
