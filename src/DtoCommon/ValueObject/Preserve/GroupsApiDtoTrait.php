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

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Preserve;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait GroupsApiDtoTrait
{
    public function addGroupsApiDto(GroupApiDtoInterface $groupsApiDto): DtoInterface
    {
        return parent::addGroupsApiDto($groupsApiDto);
    }
}
