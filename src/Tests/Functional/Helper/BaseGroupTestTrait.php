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

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Tests\Functional\Action\BaseContact;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseGroupTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected static function withContacts(array $query): array
    {
        $query[GroupApiDtoInterface::CONTACTS] = [BaseContact::defaultData()];

        return $query;
    }

    protected static function withWrappedDefaultData(array $query): array
    {
        return static::withContacts($query);
    }

    protected function createGroup(): array
    {
        $query = static::withWrappedDefaultData(static::getDefault());

        return $this->post($query);
    }

    protected function createConstraintBlankBrief(): array
    {
        $query = static::withWrappedDefaultData(static::getDefault([GroupApiDtoInterface::BRIEF => '']));

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ID]);
        Assert::assertEquals($query[GroupApiDtoInterface::BRIEF], $entity[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::BRIEF]);
        Assert::assertEquals($query[GroupApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::POSITION]);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkContactGroup($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkContactGroup($entity): void
    {
        Assert::assertArrayHasKey(GroupApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(GroupApiDtoInterface::BRIEF, $entity);
        Assert::assertArrayHasKey(GroupApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(GroupApiDtoInterface::POSITION, $entity);
    }
}
