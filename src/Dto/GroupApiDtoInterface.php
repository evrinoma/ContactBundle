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

use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\ContactInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\ContactsInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\BriefInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PositionInterface;

interface GroupApiDtoInterface extends DtoInterface, IdInterface, BriefInterface, ActiveInterface, PositionInterface, ContactInterface, ContactsInterface
{
    public const GROUP = 'group';
    public const GROUPS = GroupApiDtoInterface::GROUP.'s';
}
