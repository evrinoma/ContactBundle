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

use Evrinoma\AddressBundle\DtoCommon\ValueObject\Immutable\AddressApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\ContactApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\ContactsApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\BriefInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PositionInterface;

interface GroupApiDtoInterface extends DtoInterface, IdInterface, BriefInterface, ActiveInterface, PositionInterface, ContactApiDtoInterface, ContactsApiDtoInterface, AddressApiDtoInterface
{
    public const GROUP = 'group';
    public const GROUPS = GroupApiDtoInterface::GROUP.'s';
}
