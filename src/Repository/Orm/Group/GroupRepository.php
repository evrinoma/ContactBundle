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

namespace Evrinoma\ContactBundle\Repository\Orm\Group;

use Doctrine\Persistence\ManagerRegistry;
use Evrinoma\ContactBundle\Mediator\Group\QueryMediatorInterface;
use Evrinoma\ContactBundle\Repository\Group\GroupRepositoryInterface;
use Evrinoma\ContactBundle\Repository\Group\GroupRepositoryTrait;
use Evrinoma\UtilsBundle\Repository\Orm\RepositoryWrapper;
use Evrinoma\UtilsBundle\Repository\RepositoryWrapperInterface;

class GroupRepository extends RepositoryWrapper implements GroupRepositoryInterface, RepositoryWrapperInterface
{
    use GroupRepositoryTrait;

    /**
     * @param ManagerRegistry        $registry
     * @param string                 $entityClass
     * @param QueryMediatorInterface $mediator
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, QueryMediatorInterface $mediator)
    {
        parent::__construct($registry, $entityClass);
        $this->mediator = $mediator;
    }
}
