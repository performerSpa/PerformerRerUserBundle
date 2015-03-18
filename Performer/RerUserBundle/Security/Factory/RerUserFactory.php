<?php

namespace Performer\RerUserBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RerUserFactory extends AbstractFactory
{
    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'rer-login';
    }

    protected function getListenerId()
    {
        return 'performer_rer_user.security.authentication.listener';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication.provider.rer.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('performer_rer_user.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId))
        ;

        return $provider;
    }
}
