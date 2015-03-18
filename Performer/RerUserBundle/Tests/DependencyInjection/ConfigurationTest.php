<?php

namespace Performer\RerUserBundle\Tests\DependencyInjection;

use Performer\RerUserBundle\DependencyInjection\Configuration;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testThatCanGetConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $expected = 'Symfony\Component\Config\Definition\Builder\TreeBuilder';
        $this->assertInstanceOf($expected, $configuration->getConfigTreeBuilder());
    }
}
