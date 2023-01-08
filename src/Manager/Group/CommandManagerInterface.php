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
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Group\GroupInvalidException;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

interface CommandManagerInterface
{
    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupInvalidException
     */
    public function post(GroupApiDtoInterface $dto): GroupInterface;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupInvalidException
     * @throws GroupNotFoundException
     */
    public function put(GroupApiDtoInterface $dto): GroupInterface;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @throws GroupCannotBeRemovedException
     * @throws GroupNotFoundException
     */
    public function delete(GroupApiDtoInterface $dto): void;
}
