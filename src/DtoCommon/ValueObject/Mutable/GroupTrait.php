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

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\GroupTrait as GroupImmutableTrait;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait GroupTrait
{
    use GroupImmutableTrait;

    /**
     * @param GroupApiDtoInterface $groupApiDto
     *
     * @return DtoInterface
     */
    public function setGroupApiDto(GroupApiDtoInterface $groupApiDto): DtoInterface
    {
        $this->groupApiDto = $groupApiDto;

        return $this;
    }
}
