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

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Exception\Group\GroupProxyException;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

interface GroupQueryRepositoryInterface
{
    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return array
     *
     * @throws GroupNotFoundException
     */
    public function findByCriteria(GroupApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return GroupInterface
     *
     * @throws GroupNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): GroupInterface;

    /**
     * @param string $id
     *
     * @return GroupInterface
     *
     * @throws GroupProxyException
     * @throws ORMException
     */
    public function proxy(string $id): GroupInterface;
}
