<?php

namespace Performer\RerUserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PerformerRerUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('performer_rer_user.user_class', $config['user_class']);
        $container->setParameter('performer_rer_user.light_user_manager_class', $config['light_user_manager_class']);
        $container->setParameter('performer_rer_user.user_manager_class', $config['user_manager_class']);
        $container->setParameter('performer_rer_user.user_form_type', $config['user_form_type']);
        $container->setParameter('performer_rer_user.route', $config['route']);
        $container->setParameter('performer_rer_user.layout', $config['layout']);
        $container->setParameter('performer_rer_user.soap_service_class', $config['soap']['service_class']);
        $container->setParameter('performer_rer_user.soap_app_id', $config['soap']['app_id']);
        $container->setParameter('performer_rer_user.soap_salt', $config['soap']['salt']);
        $container->setParameter('performer_rer_user.soap_hash_wsdl', $config['soap']['hash_wsdl']);
        $container->setParameter('performer_rer_user.soap_auth_wsdl', $config['soap']['auth_wsdl']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('forms.xml');
        $loader->load('listeners.xml');
        $loader->load('soap.xml');
        $loader->load('services.xml');
    }
}
