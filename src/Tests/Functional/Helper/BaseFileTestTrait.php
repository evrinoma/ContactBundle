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

use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseFileTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createFile(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankBrief(): array
    {
        $query = static::getDefault([FileApiDtoInterface::BRIEF => '']);

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][FileApiDtoInterface::ID]);
        Assert::assertEquals($query[FileApiDtoInterface::BRIEF], $entity[PayloadModel::PAYLOAD][0][FileApiDtoInterface::BRIEF]);
        Assert::assertEquals($query[FileApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][FileApiDtoInterface::POSITION]);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkContactFile($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkContactFile($entity): void
    {
        Assert::assertArrayHasKey(FileApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(FileApiDtoInterface::BRIEF, $entity);
        Assert::assertArrayHasKey(FileApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(FileApiDtoInterface::POSITION, $entity);
    }
}
