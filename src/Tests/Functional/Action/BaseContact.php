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

use Evrinoma\ContactBundle\Dto\ContactApiDto;
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Tests\Functional\Helper\BaseContactTestTrait;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Contact\Active;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Contact\Id;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Contact\Position;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Contact\Title;
use Evrinoma\ContactBundle\Tests\Functional\ValueObject\Group\Brief;
use Evrinoma\PhoneBundle\Dto\PhoneApiDtoInterface;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseContact extends AbstractServiceTest implements BaseContactTestInterface
{
    use BaseContactTestTrait;

    public const API_GET = 'evrinoma/api/contact';
    public const API_CRITERIA = 'evrinoma/api/contact/criteria';
    public const API_DELETE = 'evrinoma/api/contact/delete';
    public const API_PUT = 'evrinoma/api/contact/save';
    public const API_POST = 'evrinoma/api/contact/create';

    protected static function getDtoClass(): string
    {
        return ContactApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
         ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(),
         ContactApiDtoInterface::ID => Id::value(),
         ContactApiDtoInterface::TITLE => Title::default(),
         ContactApiDtoInterface::POSITION => Position::value(),
         ContactApiDtoInterface::ACTIVE => Active::value(),
        ];
    }

    public function actionPost(): void
    {
        $this->createContact();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ACTIVE => Active::wrong()]);
        $find = $this->criteria($query);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ID => Id::value(), ContactApiDtoInterface::ACTIVE => Active::block(), ContactApiDtoInterface::TITLE => Title::wrong()]);
        $find = $this->criteria($query);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ACTIVE => Active::value(), ContactApiDtoInterface::ID => Id::value()]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);
        Assert::assertArrayHasKey(ContactApiDtoInterface::PHONES, $find[PayloadModel::PAYLOAD][0]);
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::PHONES]);
        Assert::assertArrayHasKey(PhoneApiDtoInterface::NUMBER, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::PHONES][0]);
        Assert::assertArrayHasKey(PhoneApiDtoInterface::COUNTRY, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::PHONES][0]);
        Assert::assertArrayHasKey(PhoneApiDtoInterface::CODE, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::PHONES][0]);

        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ACTIVE => Active::delete()]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ACTIVE => Active::delete(), ContactApiDtoInterface::TITLE => Title::value()]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);

        $group = BaseGroup::defaultData();
        $group[GroupApiDtoInterface::ACTIVE] = Active::block();
        $group[GroupApiDtoInterface::BRIEF] = Brief::value();
        $query = static::withWrappedDefaultData([ContactApiDtoInterface::DTO_CLASS => static::getDtoClass(), ContactApiDtoInterface::ACTIVE => Active::value(), ContactApiDtoInterface::ID => Id::value(), ContactApiDtoInterface::GROUP => $group]);
        $find = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);
        Assert::assertArrayHasKey(ContactApiDtoInterface::GROUPS, $find[PayloadModel::PAYLOAD][0]);
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::GROUPS]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::withWrappedDefaultData(static::getDefault([ContactApiDtoInterface::ID => Id::value(), ContactApiDtoInterface::TITLE => Title::value(), ContactApiDtoInterface::POSITION => Position::value()]));

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
        $query = static::withWrappedDefaultData(static::getDefault([ContactApiDtoInterface::ID => Id::wrong(), ContactApiDtoInterface::TITLE => Title::wrong(), ContactApiDtoInterface::POSITION => Position::wrong()]));
        $this->put($query);
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createContact();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::withWrappedDefaultData(static::getDefault([ContactApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ID], ContactApiDtoInterface::TITLE => Title::empty()]));

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::withWrappedDefaultData(static::getDefault([ContactApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ContactApiDtoInterface::ID], ContactApiDtoInterface::POSITION => Position::empty()]));

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createContact();
        $this->testResponseStatusCreated();
        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankTitle();
        $this->testResponseStatusUnprocessable();
    }
}
