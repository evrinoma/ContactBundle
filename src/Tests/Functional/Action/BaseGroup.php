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

namespace Evrinoma\ContactBundle\Tests\Functional\Action;

use Evrinoma\AddressBundle\Dto\AddressApiDtoInterface;
use Evrinoma\AddressBundle\Tests\Functional\Action\BaseAddress;
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Dto\GroupApiDto;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Tests\Functional\Helper\BaseGroupTestTrait;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Contact\Title;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Group\Active;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Group\Brief;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Group\Id;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Group\Position;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseGroup extends AbstractServiceTest implements BaseGroupTestInterface
{
    use BaseGroupTestTrait;

    public const API_GET = 'evrinoma/api/contact/group';
    public const API_CRITERIA = 'evrinoma/api/contact/group/criteria';
    public const API_DELETE = 'evrinoma/api/contact/group/delete';
    public const API_PUT = 'evrinoma/api/contact/group/save';
    public const API_POST = 'evrinoma/api/contact/group/create';

    protected static function getDtoClass(): string
    {
        return GroupApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
            GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            GroupApiDtoInterface::ID => Id::value(),
            GroupApiDtoInterface::BRIEF => Brief::default(),
            GroupApiDtoInterface::POSITION => Position::value(),
            GroupApiDtoInterface::ACTIVE => Active::value(),
            GroupApiDtoInterface::ADDRESS => BaseAddress::defaultData(),
        ];
    }

    public function actionPost(): void
    {
        $created = $this->createGroup();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $query = static::withWrappedDefaultData([GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(), GroupApiDtoInterface::ACTIVE => Active::wrong()]);
        $find = $this->criteria($query);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $query = static::withWrappedDefaultData([GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(), GroupApiDtoInterface::ID => Id::value(), GroupApiDtoInterface::ACTIVE => Active::block()]);
        $find = $this->criteria($query);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $query = static::withWrappedDefaultData([GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(), GroupApiDtoInterface::ACTIVE => Active::value(), GroupApiDtoInterface::ID => Id::value()]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);
        Assert::assertArrayHasKey(GroupApiDtoInterface::CONTACTS, $find[PayloadModel::PAYLOAD][0]);
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::CONTACTS]);
        Assert::assertArrayHasKey(GroupApiDtoInterface::ADDRESS, $find[PayloadModel::PAYLOAD][0]);
        Assert::assertArrayHasKey(AddressApiDtoInterface::ADDRESS, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ADDRESS]);
        Assert::assertArrayHasKey(AddressApiDtoInterface::COUNTRY, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ADDRESS]);
        Assert::assertArrayHasKey(AddressApiDtoInterface::COUNTRY_CODE, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ADDRESS]);
        Assert::assertArrayHasKey(AddressApiDtoInterface::TOWN, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ADDRESS]);

        $query = static::withWrappedDefaultData([GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(), GroupApiDtoInterface::ACTIVE => Active::delete()]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(5, $find[PayloadModel::PAYLOAD]);

        $contact = BaseContact::defaultData();
        $contact[ContactApiDtoInterface::ACTIVE] = Active::block();
        $contact[ContactApiDtoInterface::TITLE] = Title::value();
        $query = static::withWrappedDefaultData([GroupApiDtoInterface::DTO_CLASS => static::getDtoClass(), GroupApiDtoInterface::ACTIVE => Active::value(), GroupApiDtoInterface::ID => Id::value(), GroupApiDtoInterface::CONTACT => $contact]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);
        Assert::assertArrayHasKey(GroupApiDtoInterface::CONTACTS, $find[PayloadModel::PAYLOAD][0]);
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::CONTACTS]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::withWrappedDefaultData(static::getDefault([GroupApiDtoInterface::ID => Id::value(), GroupApiDtoInterface::BRIEF => Brief::value()]));

        $find = $this->assertGet(Id::value());

        $updated = $this->put($query);
        $this->testResponseStatusOK();

        $this->compareResults($find, $updated, $query);
    }

    public function actionGet(): void
    {
        $find = $this->assertGet(Id::value());
    }

    public function actionGetNotFound(): void
    {
        $response = $this->get(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteNotFound(): void
    {
        $response = $this->delete(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteUnprocessable(): void
    {
        $response = $this->delete(Id::empty());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPutNotFound(): void
    {
        $query = static::withWrappedDefaultData(static::getDefault([GroupApiDtoInterface::ID => Id::wrong(),            GroupApiDtoInterface::BRIEF => Brief::wrong()]));
        $this->put($query);
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createGroup();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::withWrappedDefaultData(static::getDefault([GroupApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ID], GroupApiDtoInterface::BRIEF => Brief::empty()]));

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::withWrappedDefaultData(static::getDefault([GroupApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][GroupApiDtoInterface::ID], GroupApiDtoInterface::POSITION => Position::empty()]));

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $created = $this->createGroup();
        $this->testResponseStatusCreated();

        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankBrief();
        $this->testResponseStatusUnprocessable();
    }
}
