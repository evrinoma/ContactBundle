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

interface GroupApiDtoInterface
{
    public const GROUP = BaseGroupApiDtoInterface::GROUP;

    public function hasGroupApiDto(): bool;

    public function getGroupApiDto(): BaseGroupApiDtoInterface;
}
