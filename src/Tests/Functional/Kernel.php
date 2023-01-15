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

namespace Evrinoma\ContactBundle\Tests\Functional;

use Evrinoma\AddressBundle\EvrinomaAddressBundle;
use Evrinoma\ContactBundle\EvrinomaContactBundle;
use Evrinoma\DtoBundle\EvrinomaDtoBundle;
use Evrinoma\MailBundle\EvrinomaMailBundle;
use Evrinoma\PhoneBundle\EvrinomaPhoneBundle;
use Evrinoma\TestUtilsBundle\Kernel\AbstractApiKernel;

/**
 * Kernel.
 */
class Kernel extends AbstractApiKernel
{
    protected string $bundlePrefix = 'ContactBundle';
    protected string $rootDir = __DIR__;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array_merge(
            parent::registerBundles(), [
                new EvrinomaDtoBundle(),
                new EvrinomaAddressBundle(),
                new EvrinomaMailBundle(),
                new EvrinomaPhoneBundle(),
                new EvrinomaContactBundle(),
            ]
        );
    }

    protected function getBundleConfig(): array
    {
        return ['framework.yaml', 'jms_serializer.yaml'];
    }
}
