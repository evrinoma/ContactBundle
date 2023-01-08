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

namespace Evrinoma\ContactBundle;

use Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Complex\ContactPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Property\ContactPass as PropertyContactPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Property\GroupPass as PropertyGroupPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\DecoratorPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\MapEntityPass;
use Evrinoma\ContactBundle\DependencyInjection\Compiler\ServicePass;
use Evrinoma\ContactBundle\DependencyInjection\EvrinomaContactExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaContactBundle extends Bundle
{
    public const BUNDLE = 'contact';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass(new PropertyContactPass())
            ->addCompilerPass(new PropertyGroupPass())
            ->addCompilerPass(new ContactPass())
            ->addCompilerPass(new DecoratorPass())
            ->addCompilerPass(new ServicePass())
        ;
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EvrinomaContactExtension();
        }

        return $this->extension;
    }
}
