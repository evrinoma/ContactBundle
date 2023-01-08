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
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Exception\Group\GroupProxyException;
use Evrinoma\ContactBundle\Mediator\Group\QueryMediatorInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

trait GroupRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param GroupInterface $type
     *
     * @return bool
     *
     * @throws GroupCannotBeSavedException
     * @throws ORMException
     */
    public function save(GroupInterface $type): bool
    {
        try {
            $this->persistWrapped($type);
        } catch (ORMInvalidArgumentException $e) {
            throw new GroupCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param GroupInterface $type
     *
     * @return bool
     */
    public function remove(GroupInterface $type): bool
    {
        return true;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return array
     *
     * @throws GroupNotFoundException
     */
    public function findByCriteria(GroupApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $types = $this->mediator->getResult($dto, $builder);

        if (0 === \count($types)) {
            throw new GroupNotFoundException('Cannot find type by findByCriteria');
        }

        return $types;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws GroupNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): GroupInterface
    {
        /** @var GroupInterface $type */
        $type = $this->findWrapped($id);

        if (null === $type) {
            throw new GroupNotFoundException("Cannot find type with id $id");
        }

        return $type;
    }

    /**
     * @param string $id
     *
     * @return GroupInterface
     *
     * @throws GroupProxyException
     * @throws ORMException
     */
    public function proxy(string $id): GroupInterface
    {
        $type = $this->referenceWrapped($id);

        if (!$this->containsWrapped($type)) {
            throw new GroupProxyException("Proxy doesn't exist with $id");
        }

        return $type;
    }
}
