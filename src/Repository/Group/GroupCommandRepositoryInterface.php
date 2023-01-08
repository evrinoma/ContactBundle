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

namespace Evrinoma\ContactBundle\Repository\Group;

use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeSavedException;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

interface GroupCommandRepositoryInterface
{
    /**
     * @param GroupInterface $type
     *
     * @return bool
     *
     * @throws GroupCannotBeSavedException
     */
    public function save(GroupInterface $type): bool;

    /**
     * @param GroupInterface $type
     *
     * @return bool
     *
     * @throws GroupCannotBeRemovedException
     */
    public function remove(GroupInterface $type): bool;
}
