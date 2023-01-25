<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ContactBundle\DependencyInjection;

use Evrinoma\ContactBundle\EvrinomaContactBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(EvrinomaContactBundle::BUNDLE);
        $rootNode = $treeBuilder->getRootNode();
        $supportedDrivers = ['orm'];

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('db_driver')
            ->validate()
            ->ifNotInArray($supportedDrivers)
            ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
            ->end()
            ->cannotBeOverwritten()
            ->defaultValue('orm')
            ->end()
            ->scalarNode('factory_contact')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::ENTITY_FACTORY_CONTACT)->end()
            ->scalarNode('factory_group')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::ENTITY_FACTORY_GROUP)->end()
            ->scalarNode('entity_contact')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::ENTITY_BASE_CONTACT)->end()
            ->scalarNode('entity_group')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::ENTITY_BASE_GROUP)->end()
            ->scalarNode('constraints')->defaultTrue()->info('This option is used to enable/disable basic contact constraints')->end()
            ->scalarNode('dto_contact')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::DTO_BASE_CONTACT)->info('This option is used to dto class override')->end()
            ->scalarNode('dto_group')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::DTO_BASE_GROUP)->info('This option is used to dto class override')->end()
            ->arrayNode('decorates')->addDefaultsIfNotSet()->children()
            ->scalarNode('command_contact')->defaultNull()->info('This option is used to command contact decoration')->end()
            ->scalarNode('query_contact')->defaultNull()->info('This option is used to query contact decoration')->end()
            ->scalarNode('command_group')->defaultNull()->info('This option is used to command group decoration')->end()
            ->scalarNode('query_group')->defaultNull()->info('This option is used to query group decoration')->end()
            ->end()->end()
            ->arrayNode('services')->addDefaultsIfNotSet()->children()
            ->scalarNode('pre_validator_contact')->defaultNull()->info('This option is used to pre_validator overriding contact')->end()
            ->scalarNode('handler_contact')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::HANDLER)->info('This option is used to handler override contact')->end()
            ->scalarNode('pre_validator_group')->defaultNull()->info('This option is used to pre_validator overriding group')->end()
            ->scalarNode('handler_group')->cannotBeEmpty()->defaultValue(EvrinomaContactExtension::HANDLER)->info('This option is used to handler override group')->end()
            ->end()->end()
            ->end();

        return $treeBuilder;
    }
}
