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

namespace Evrinoma\ContactBundle\Tests\Functional\Controller;

use Evrinoma\AddressBundle\Fixtures\FixtureInterface as AddressFixtureInterface;
use Evrinoma\ContactBundle\Fixtures\FixtureInterface;
use Evrinoma\MailBundle\Fixtures\FixtureInterface as MailFixtureInterface;
use Evrinoma\PhoneBundle\Fixtures\FixtureInterface as PhoneFixtureInterface;
use Evrinoma\TestUtilsBundle\Action\ActionTestInterface;
use Evrinoma\TestUtilsBundle\Functional\Orm\AbstractFunctionalTest;
use Psr\Container\ContainerInterface;

/**
 * @group functional
 */
final class GroupApiControllerTest extends AbstractFunctionalTest
{
    protected string $actionServiceName = 'evrinoma.contact.test.functional.action.group';

    protected function getActionService(ContainerInterface $container): ActionTestInterface
    {
        return $container->get($this->actionServiceName);
    }

    public static function getFixtures(): array
    {
        return [
            FixtureInterface::GROUP_FIXTURES,
            FixtureInterface::CONTACT_FIXTURES,
            AddressFixtureInterface::ADDRESS_FIXTURES,
            PhoneFixtureInterface::PHONE_FIXTURES,
            MailFixtureInterface::MAIL_FIXTURES,
            ];
    }
}
