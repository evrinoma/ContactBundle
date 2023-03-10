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

namespace Evrinoma\ContactBundle\Factory\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

interface FactoryInterface
{
    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     */
    public function create(GroupApiDtoInterface $dto): GroupInterface;
}
