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

namespace Evrinoma\ContactBundle\Mediator\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeRemovedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeCreatedException;
use Evrinoma\ContactBundle\Exception\Group\GroupCannotBeSavedException;
use Evrinoma\ContactBundle\Manager\Group\QueryManagerInterface as GroupQueryManagerInterface;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private GroupQueryManagerInterface $groupQueryManager;

    public function __construct(GroupQueryManagerInterface $groupQueryManager)
    {
        $this->groupQueryManager = $groupQueryManager;
    }

    public function onUpdate(DtoInterface $dto, $entity): ContactInterface
    {
        /* @var $dto ContactApiDtoInterface */
        try {
            foreach ($entity->getGroups() as $group) {
                $entity->removeGroup($group);
            }

            foreach ($dto->getGroupsApiDto() as $groupApiDto) {
                $entity->addGroup($this->groupQueryManager->proxy($groupApiDto));
            }
        } catch (\Exception $e) {
            throw new GroupCannotBeSavedException($e->getMessage());
        }

        $entity
            ->setTitle($dto->getTitle())
            ->setPosition($dto->getPosition())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        try {
            foreach ($entity->getGroups() as $group) {
                $entity->removeGroup($group);
            }
        } catch (\Exception $e) {
            throw new ContactCannotBeRemovedException($e->getMessage());
        }

        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): ContactInterface
    {
        /* @var $dto ContactApiDtoInterface */
        try {
            foreach ($dto->getGroupsApiDto() as $groupApiDto) {
                $entity->addGroup($this->groupQueryManager->proxy($groupApiDto));
            }
        } catch (\Exception $e) {
            throw new GroupCannotBeCreatedException($e->getMessage());
        }

        $entity
             ->setTitle($dto->getTitle())
             ->setPosition($dto->getPosition())
             ->setCreatedAt(new \DateTimeImmutable())
             ->setActiveToActive();

        return $entity;
    }
}
