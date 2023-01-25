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

use Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Property\ContactPass as PropertyContactPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Property\GroupPass as PropertyGroupPass;
use Evrinoma\ContactBundle\Dto\ContactApiDto;
use Evrinoma\ContactBundle\Dto\GroupApiDto;
use Evrinoma\ContactBundle\Entity\Contact\BaseContact;
use Evrinoma\ContactBundle\Entity\Group\BaseGroup;
use Evrinoma\ContactBundle\EvrinomaContactBundle;
use Evrinoma\ContactBundle\Factory\Contact\Factory as ContactFactory;
use Evrinoma\ContactBundle\Factory\Group\Factory as GroupFactory;
use Evrinoma\ContactBundle\Mediator\Contact\QueryMediatorInterface as ContactQueryMediatorInterface;
use Evrinoma\ContactBundle\Mediator\Group\QueryMediatorInterface as GroupQueryMediatorInterface;
use Evrinoma\UtilsBundle\Adaptor\AdaptorRegistry;
use Evrinoma\UtilsBundle\DependencyInjection\HelperTrait;
use Evrinoma\UtilsBundle\Handler\BaseHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class EvrinomaContactExtension extends Extension
{
    use HelperTrait;

    public const ENTITY = 'Evrinoma\ContactBundle\Entity';
    public const MODEL = 'Evrinoma\ContactBundle\Model';
    public const ENTITY_FACTORY_CONTACT = ContactFactory::class;
    public const ENTITY_FACTORY_GROUP = GroupFactory::class;
    public const ENTITY_BASE_CONTACT = BaseContact::class;
    public const ENTITY_BASE_GROUP = BaseGroup::class;
    public const DTO_BASE_CONTACT = ContactApiDto::class;
    public const DTO_BASE_GROUP = GroupApiDto::class;
    public const HANDLER = BaseHandler::class;

    /**
     * @var array
     */
    private static array $doctrineDrivers = [
        'orm' => [
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ],
    ];

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ('prod' !== $container->getParameter('kernel.environment')) {
            $loader->load('fixtures.yml');
        }

        if ('test' === $container->getParameter('kernel.environment')) {
            $loader->load('tests.yml');
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (self::ENTITY_FACTORY_CONTACT !== $config['factory_contact']) {
            $this->wireFactory($container, $config['factory_contact'], $config['entity_contact']);
        } else {
            $definitionFactory = $container->getDefinition('evrinoma.'.$this->getAlias().'.contact.factory');
            $definitionFactory->setArgument(0, $config['entity_contact']);
        }

        if (self::ENTITY_FACTORY_GROUP !== $config['factory_group']) {
            $this->wireFactory($container, $config['factory_group'], $config['entity_group']);
        } else {
            $definitionFactory = $container->getDefinition('evrinoma.'.$this->getAlias().'.group.factory');
            $definitionFactory->setArgument(0, $config['entity_group']);
        }

        $registry = null;

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'orm' === $config['db_driver']) {
            $loader->load('doctrine.yml');
            $container->setAlias('evrinoma.'.$this->getAlias().'.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            $registry = new Reference('evrinoma.'.$this->getAlias().'.doctrine_registry');
            $container->setParameter('evrinoma.'.$this->getAlias().'.backend_type_'.$config['db_driver'], true);
            $objectManager = $container->getDefinition('evrinoma.'.$this->getAlias().'.object_manager');
            $objectManager->setFactory([$registry, 'getManager']);
        }

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'api' === $config['db_driver']) {
            // @ToDo
        }

        if (null !== $registry) {
            $this->wireAdaptorRegistry($container, $registry);
        }

        $this->wireMediator($container, ContactQueryMediatorInterface::class, $config['db_driver'], 'contact');
        $this->wireMediator($container, GroupQueryMediatorInterface::class, $config['db_driver'], 'group');

        $this->remapParametersNamespaces(
            $container,
            $config,
            [
                '' => [
                    'db_driver' => 'evrinoma.'.$this->getAlias().'.storage',
                    'entity_contact' => 'evrinoma.'.$this->getAlias().'.entity_contact',
                    'entity_group' => 'evrinoma.'.$this->getAlias().'.entity_group',
                ],
            ]
        );

        if ($registry && isset(self::$doctrineDrivers[$config['db_driver']])) {
            $this->wireRepository($container, $registry, ContactQueryMediatorInterface::class, 'contact', $config['entity_contact'], $config['db_driver']);
            $this->wireRepository($container, $registry, GroupQueryMediatorInterface::class, 'group', $config['entity_group'], $config['db_driver']);
        }

        $this->wireController($container, 'contact', $config['dto_contact']);
        $this->wireController($container, 'group', $config['dto_group']);

        $this->wireValidator($container, 'contact', $config['entity_contact']);
        $this->wireValidator($container, 'group', $config['entity_group']);

        if ($config['constraints']) {
            $loader->load('validation.yml');
        }

        $this->wireConstraintTag($container);

        if ($config['decorates']) {
            $remap = [];
            foreach ($config['decorates'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'command_contact':
                            $remap['command_contact'] = 'evrinoma.'.$this->getAlias().'.contact.decorates.command';
                            break;
                        case 'query_contact':
                            $remap['query_contact'] = 'evrinoma.'.$this->getAlias().'.contact.decorates.query';
                            break;
                        case 'command_group':
                            $remap['command_group'] = 'evrinoma.'.$this->getAlias().'.group.decorates.command';
                            break;
                        case 'query_group':
                            $remap['query_group'] = 'evrinoma.'.$this->getAlias().'.group.decorates.query';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['decorates'],
                ['' => $remap]
            );
        }

        if ($config['services']) {
            $remap = [];
            foreach ($config['services'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'pre_validator_contact':
                            $remap['pre_validator_contact'] = 'evrinoma.'.$this->getAlias().'.contact.services.pre.validator';
                            break;
                        case 'handler_contact':
                            $remap['handler_contact'] = 'evrinoma.'.$this->getAlias().'.contact.services.handler';
                            break;
                        case 'pre_validator_group':
                            $remap['pre_validator_group'] = 'evrinoma.'.$this->getAlias().'.group.services.pre.validator';
                            break;
                        case 'handler_group':
                            $remap['handler_group'] = 'evrinoma.'.$this->getAlias().'.group.services.handler';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['services'],
                ['' => $remap]
            );
        }
    }

    private function wireConstraintTag(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $key => $definition) {
            switch (true) {
                case false !== str_contains($key, PropertyContactPass::CONTACT_CONSTRAINT):
                    $definition->addTag(PropertyContactPass::CONTACT_CONSTRAINT);
                    break;
                case false !== str_contains($key, PropertyGroupPass::GROUP_CONSTRAINT):
                    $definition->addTag(PropertyGroupPass::GROUP_CONSTRAINT);
                    break;
//                case false !== strpos($key, ContactPass::CONTACT_CONSTRAINT):
//                    $definition->addTag(ContactPass::CONTACT_CONSTRAINT);
//                    break;
                default:
            }
        }
    }

    private function wireMediator(ContainerBuilder $container, string $class, string $driver, string $name): void
    {
        $definitionQueryMediator = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.query.'.$driver.'.mediator');
        $container->addDefinitions([$class => $definitionQueryMediator]);
    }

    private function wireAdaptorRegistry(ContainerBuilder $container, Reference $registry): void
    {
        $definitionAdaptor = new Definition(AdaptorRegistry::class);
        $definitionAdaptor->addArgument($registry);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.adaptor' => $definitionAdaptor]);
    }

    private function wireRepository(ContainerBuilder $container, Reference $registry, string $madiator, string $name, string $class, string $driver): void
    {
        $definitionRepository = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.'.$driver.'.repository');
        $definitionQueryMediator = $container->getDefinition($madiator);
        $definitionRepository->setArgument(0, $registry);
        $definitionRepository->setArgument(1, $class);
        $definitionRepository->setArgument(2, $definitionQueryMediator);
        $array = $definitionRepository->getArguments();
        ksort($array);
        $definitionRepository->setArguments($array);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.repository' => $definitionRepository]);
    }

    private function wireFactory(ContainerBuilder $container, string $name, string $class, string $paramClass): void
    {
        $container->removeDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $definitionFactory = new Definition($class);
        $definitionFactory->addArgument($paramClass);
        $alias = new Alias('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.factory' => $definitionFactory]);
        $container->addAliases([$class => $alias]);
    }

    private function wireController(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.api.controller');
        $definitionApiController->setArgument(4, $class);
    }

    private function wireValidator(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.validator');
        $definitionApiController->setArgument(0, new Reference('validator'));
        $definitionApiController->setArgument(1, $class);
    }

    public function getAlias()
    {
        return EvrinomaContactBundle::BUNDLE;
    }
}
