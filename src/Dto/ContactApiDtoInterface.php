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

namespace Evrinoma\ContactBundle\Dto;

use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\GroupApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\GroupsApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\TitleInterface;
use Evrinoma\MailBundle\DtoCommon\ValueObject\Immutable\MailsApiDtoInterface;
use Evrinoma\PhoneBundle\DtoCommon\ValueObject\Immutable\PhonesApiDtoInterface;

interface ContactApiDtoInterface extends DtoInterface, IdInterface, TitleInterface, PositionInterface, ActiveInterface, GroupApiDtoInterface, GroupsApiDtoInterface, PhonesApiDtoInterface, MailsApiDtoInterface
{
    public const CONTACT = 'contact';
    public const CONTACTS = ContactApiDtoInterface::CONTACT.'s';
}
