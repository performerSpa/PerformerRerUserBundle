<?php

namespace Performer\RerUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('performer_rer_user');

        $rootNode
            ->children()
                ->scalarNode('user_class')
                    ->isRequired()
                ->end()
                ->scalarNode('light_user_manager_class')
                    ->cannotBeEmpty()
                    ->defaultValue('Performer\RerUserBundle\Manager\LightUserManager')
                ->end()
                ->scalarNode('user_manager_class')
                    ->cannotBeEmpty()
                    ->defaultValue('Performer\RerUserBundle\Manager\UserManager')
                ->end()
                ->scalarNode('user_form_type')
                    ->cannotBeEmpty()
                    ->defaultValue('Performer\RerUserBundle\Form\Type\UserType')
                ->end()
                ->scalarNode('route')
                    ->cannotBeEmpty()
                    ->defaultValue('user')
                ->end()
                ->scalarNode('layout')
                    ->cannotBeEmpty()
                    ->defaultValue('PerformerRerUserBundle::layout.html.twig')
                ->end()
                ->arrayNode('soap')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service_class')
                            ->cannotBeEmpty()
                            ->defaultValue('Performer\RerUserBundle\Security\Soap\Service')
                        ->end()
                        ->scalarNode('app_id')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('salt')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('hash_wsdl')
                            ->cannotBeEmpty()
                            ->defaultValue('https://wwwservizi.regione.emilia-romagna.it/WebServices/' .
                                           'AutenticazioneCentralizzata/HashHelper.asmx?WSDL')
                        ->end()
                        ->scalarNode('auth_wsdl')
                            ->cannotBeEmpty()
                            ->defaultValue('https://wwwservizi.regione.emilia-romagna.it/WebServices/' .
                                           'AutenticazioneCentralizzata/Authentication.asmx?WSDL')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
