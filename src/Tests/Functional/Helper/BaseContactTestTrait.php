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

namespace Evrinoma\ContactBundle\Tests\Functional\Helper;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Tests\Functional\Action\BaseGroup;
use Evrinoma\MailBundle\Tests\Functional\Action\BaseMail;
use Evrinoma\PhoneBundle\Tests\Functional\Action\BasePhone;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseContactTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected static function withMails(array $query): array
    {
        $query[ContactApiDtoInterface::MAILS] = [BaseMail::defaultData()];

        return $query;
    }

    protected static function withPhones(array $query): array
    {
        $query[ContactApiDtoInterface::PHONES] = [BasePhone::defaultData()];

        return $query;
    }

    protected static function withGroups(array $query): array
    {
        $query[ContactApiDtoInterface::GROUPS] = [BaseGroup::defaultData()];

        return $query;
    }

    protected static function withWrappedDefaultData(array $query): array
    {
        return static::withMails(static::withPhones(static::withGroups($query)));
    }

    protected function createContact(): array
    {
        $query = static::withWrappedDefaultData(static::getDefault());

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ID]);
        Assert::assertEquals($query[ContactApiDtoInterface::TITLE], $entity[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::TITLE]);
        Assert::assertEquals($query[ContactApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::POSITION]);
    }

    protected function createConstraintBlankTitle(): array
    {
        $query = static::withWrappedDefaultData(static::getDefault([ContactApiDtoInterface::TITLE => '']));

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkContact($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkContact($entity): void
    {
        Assert::assertArrayHasKey(ContactApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(ContactApiDtoInterface::TITLE, $entity);
        Assert::assertArrayHasKey(ContactApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(ContactApiDtoInterface::POSITION, $entity);
    }
}
