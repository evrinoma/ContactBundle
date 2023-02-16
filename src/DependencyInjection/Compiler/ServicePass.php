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

class ServicePass extends AbstractRecursivePass
{
    private array $services = ['contact', 'group'];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $alias) {
            $this->wireServices($container, $alias);
        }
    }

    private function wireServices(ContainerBuilder $container, string $name)
    {
        $servicePreValidator = $container->hasParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.services.pre.validator');
        if ($servicePreValidator) {
            $servicePreValidator = $container->getParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.services.pre.validator');
            $preValidator = $container->getDefinition($servicePreValidator);
            $facade = $container->getDefinition('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.facade');
            $facade->setArgument(3, $preValidator);
        }
        $serviceHandler = $container->hasParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.services.handler');
        if ($serviceHandler) {
            $serviceHandler = $container->getParameter('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.services.handler');
            $handler = $container->getDefinition($serviceHandler);
            $facade = $container->getDefinition('evrinoma.'.EvrinomaContactBundle::BUNDLE.'.'.$name.'.facade');
            $facade->setArgument(4, $handler);
        }
    }
}
