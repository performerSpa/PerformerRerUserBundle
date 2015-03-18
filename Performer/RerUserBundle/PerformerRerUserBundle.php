<?php

namespace Performer\RerUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Performer\RerUserBundle\Security\Factory\RerUserFactory;

class PerformerRerUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new RerUserFactory());
    }
}
