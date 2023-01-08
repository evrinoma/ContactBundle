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
use Evrinoma\ContactBundle\Repository\Group\GroupQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private GroupQueryRepositoryInterface $repository;

    public function __construct(GroupQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return array
     *
     * @throws GroupNotFoundException
     */
    public function criteria(GroupApiDtoInterface $dto): array
    {
        try {
            $contact = $this->repository->findByCriteria($dto);
        } catch (GroupNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupProxyException
     */
    public function proxy(GroupApiDtoInterface $dto): GroupInterface
    {
        try {
            if ($dto->hasId()) {
                $contact = $this->repository->proxy($dto->idToString());
            } else {
                throw new GroupProxyException('Id value is not set while trying get proxy object');
            }
        } catch (GroupProxyException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     *
     * @throws GroupNotFoundException
     */
    public function get(GroupApiDtoInterface $dto): GroupInterface
    {
        try {
            $contact = $this->repository->find($dto->idToString());
        } catch (GroupNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }
}
