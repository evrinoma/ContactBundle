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

namespace Evrinoma\ContactBundle\Dto\Preserve;

use Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable\GroupApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable\GroupsApiDtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleInterface;
use Evrinoma\MailBundle\DtoCommon\ValueObject\Mutable\MailsApiDtoInterface;
use Evrinoma\PhoneBundle\DtoCommon\ValueObject\Mutable\PhonesApiDtoInterface;

interface ContactApiDtoInterface extends IdInterface, TitleInterface, PositionInterface, ActiveInterface, GroupApiDtoInterface, GroupsApiDtoInterface, PhonesApiDtoInterface, MailsApiDtoInterface
{
}
