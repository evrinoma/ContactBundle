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

namespace Evrinoma\ContactBundle\Manager\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Exception\Group\GroupProxyException;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

interface QueryManagerInterface
{
    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return array
     *
     * @throws GroupNotFoundException
     */
    public function criteria(GroupApiDtoInterface $dto): array;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupNotFoundException
     */
    public function get(GroupApiDtoInterface $dto): GroupInterface;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupProxyException
     */
    public function proxy(GroupApiDtoInterface $dto): GroupInterface;
}
