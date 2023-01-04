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

namespace Evrinoma\ContactBundle\Repository\Orm\Contact;

use Doctrine\Persistence\ManagerRegistry;
use Evrinoma\ContactBundle\Mediator\Contact\QueryMediatorInterface;
use Evrinoma\ContactBundle\Repository\Contact\ContactRepositoryInterface;
use Evrinoma\ContactBundle\Repository\Contact\ContactRepositoryTrait;
use Evrinoma\UtilsBundle\Repository\Orm\RepositoryWrapper;
use Evrinoma\UtilsBundle\Repository\RepositoryWrapperInterface;

class ContactRepository extends RepositoryWrapper implements ContactRepositoryInterface, RepositoryWrapperInterface
{
    use ContactRepositoryTrait;

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
