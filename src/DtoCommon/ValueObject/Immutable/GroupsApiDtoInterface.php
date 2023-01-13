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

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface as BaseGroupApiDtoInterface;

interface GroupsApiDtoInterface
{
    public const GROUPS = BaseGroupApiDtoInterface::GROUPS;

    public function hasGroupsApiDto(): bool;

    public function getGroupsApiDto(): array;
}
