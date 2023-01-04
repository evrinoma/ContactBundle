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

namespace Evrinoma\ContactBundle\DependencyInjection\Compiler;

use Evrinoma\ContactBundle\EvrinomaContactBundle;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DecoratorPass extends AbstractRecursivePass
{
    private array $services = ['contact'];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $alias) {
            $this->wireDecorates($container, $alias);
        }
    }

    private function wireDecorates(ContainerBuilder $container, string $name)
    {
        $decoratorQuery = $container->hasParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.decorates.query');
        if ($decoratorQuery) {
            $decoratorQuery = $container->getParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.decorates.query');
            $queryMediator = $container->getDefinition($decoratorQuery);
            $repository = $container->getDefinition('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.repository');
            $repository->setArgument(2, $queryMediator);
        }
        $decoratorCommand = $container->hasParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.decorates.command');
        if ($decoratorCommand) {
            $decoratorCommand = $container->getParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.decorates.command');
            $commandMediator = $container->getDefinition($decoratorCommand);
            $commandManager = $container->getDefinition('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.command.manager');
            $commandManager->setArgument(3, $commandMediator);
        }
    }
}
